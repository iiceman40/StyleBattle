// TODO wrap set winner and get new opponent in one PHP function?
// TODO function getTwoStyles in PHP?
$(document).ready(function(){
	var msg_newStyleRequirements = 'Please upload an image and enter a description!';

	var viewModel = function(){
		var self = this;

		var Style = function(data){
			var thisStyle = this;

			this.id = ko.observable(data.id);
			this.image = ko.observable(data.image);
			this.rating = ko.observable(data.rating);
			this.description = ko.observable(data.description);
			this.loading = ko.observable(false);

			this.setWinner = function(){
				if(thisStyle == self.style1()) theOtherStyle = self.style2;
				else theOtherStyle = self.style1;
				if(!thisStyle.loading() && !theOtherStyle().loading()){
					// loading status TODO decide between loading image and just updating rating?
					thisStyle.loading(true);
					theOtherStyle().loading(true);
					$.post("ajax/setwinner.php", { idwinner: thisStyle.id(), idlooser: theOtherStyle().id() }, function( data ) {
						result = $.parseJSON( data );
						thisStyle.rating(result.rating);
						setStyle(theOtherStyle, thisStyle.id(), theOtherStyle().id());
						thisStyle.loading(false);
					});
				}
			}
		}

		var User = function(data){
			var thisUser = this;

			this.id = ko.observable(data.id);
			this.email = ko.observable(data.email);
			this.password = ko.observable(data.password);
			this.nickname = ko.observable(data.nickname);

			this.styles = ko.observableArray([]);

			if(this.email())
				$.post( "ajax/getuserstyles.php", {userid: thisUser.id()}, function( data ) {
					result = $.parseJSON( data );
					thisUser.styles(result.styles.reverse());

					// TODO
					$('#myStyles').masonry({
						itemSelector : '.panel'
					});
				});
		}

		// observables
		this.page = ko.observable('battle');

		this.loginMsg = ko.observable();
		this.stayLoggedIn = ko.observable();
		this.loggedInAs = ko.observable(new User({nickname: 'Guest'}));
		if($.cookie("styleBattleLogin")) self.loggedInAs( new User($.parseJSON($.cookie("styleBattleLogin"))) );

		this.style1 = ko.observable( new Style({}) );
		this.style2 = ko.observable( new Style({}) );

		this.newStyleImageName = ko.observable();
		this.newStyleImageId = ko.observable();
		this.newPathToFile = ko.observable();
		this.newStyleDescription = ko.observable();
		this.newStyleError = ko.observable();

		// computed observables
		this.disableNewStyle = ko.computed(function(){
			if( self.newStyleImageId() && self.newStyleDescription() && self.newPathToFile()){
				self.newStyleError('');
				return false;
			}
			else {
				self.newStyleError(msg_newStyleRequirements);
				return true;
			}
		}, this);

		// initiate styles
		initStyles();

		// operations
		this.addStyle = function(){
			self.newStyleError('Saving...');
			$.post( "ajax/addstyle.php", {imageid: self.newStyleImageId(), description: self.newStyleDescription(), pathToFile: self.newPathToFile()}, function( data ) {
				result = $.parseJSON(data);
				self.loggedInAs().styles.unshift(result);

				self.newStyleImageName('');
				self.newStyleImageId('');
				self.newPathToFile('');
				self.newStyleDescription('');

				$('#addStyleModal').modal('hide');
				self.newStyleError(msg_newStyleRequirements);
			});
		}
		this.removeStyle = function(style){
			$.post( "ajax/deletestyle.php", {styleid: style.id}, function( data ) {
				self.loggedInAs().styles.remove(style);
			});
		}
		this.goToPage = function(data){
			self.page(data);
		}
		this.login = function(){
			$("#loginForm").submit();
			email = $('#loginEmail').val();
			password = $('#loginPassword').val();
			decodedPassword = $.md5(password);
			$.post("ajax/login.php", { email: email, password: decodedPassword }, function( data ) {
				result = $.parseJSON( data );
				if(result.success){
					self.loginMsg('<p class="alert alert-success">Logging in...</p>');
					self.loggedInAs(new User(result));
					if(self.stayLoggedIn) $.cookie("styleBattleLogin", ko.toJSON(self.loggedInAs()));
					self.loginMsg('Logged in as '+self.loggedInAs().nickname());
					$('#loginModal').modal('hide');
				} else {
					self.loginMsg('<p class="alert alert-danger">Email oder Passwort falsch.</p>');
				}

			});
		}
		this.logout = function(){
			self.loggedInAs(new User({nickname: 'Guest'}));
			$.removeCookie("styleBattleLogin");
		}

		// Helper Functions
		function initStyles(){
			$.post( "ajax/getstyle.php", function( data ) {
				self.style1( new Style($.parseJSON(data)) );
				setStyle(self.style2, self.style1().id(), self.style1().id());
			});
		}
		function setStyle(style, idwinner, idlooser){
			$.post( "ajax/getstyle.php", {
				idwinner: idwinner,
				idlooser: idlooser
			}, function( data ) {
				style( new Style($.parseJSON(data)) );
			});
		}

		$("#addImageForm").dropzone({
			maxFiles: 1,
			addRemoveLinks: true,
			init: function() {
				this.on("success", function(file, response) {
					result = $.parseJSON(response)
					self.newStyleImageName(result.filename);
					self.newStyleImageId(result.id);
					self.newPathToFile(result.pathToFile);
					this.removeAllFiles();
				});
			}
		});
	}

	ko.applyBindings(new viewModel);
});