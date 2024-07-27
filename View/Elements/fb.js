var ajax_url = '/eduting/';
	window.fbAsyncInit = function() 
	{
	    FB.init({
	      appId      : '1718875144994890',
	      xfbml      : true,
		  status	 : true,
		  cookie	 : true,
	      version    : 'v2.6'
	    });
    };

	(function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

   
  function statusChangeCallback(response) 
  {
    if (response.status === 'connected') {
	    FB.api('/me?fields=name,email,gender,location,hometown', function(response) 
		{
			var backurl_email=$('#backurl_email').text();
			var backurl=location.href.split('back_url=');
			var encoded_backurl='';
			if (typeof backurl[1] !== "undefined")
			{
				encoded_backurl=backurl[1];
			}
			var fb_id=response.id;
			var email=response.email;
			$('#fb_userLoginForm #fb_id').val(response.id);
			$('#fb_userLoginForm #fb_email').val(response.email);
			$('#fb_userLoginForm #fb_name').val(response.name);
			$('#fb_userLoginForm #fb_gender').val(response.gender);
			var fb_location='';
			var fb_hometown='';
			if (typeof response.location !== "undefined")
			{
				fb_location=response.location.name;
			}
			if (typeof response.hometown !== "undefined")
			{
				fb_hometown=response.hometown.name;
			}
			
			$('#fb_userLoginForm #fb_location').val(fb_location);
			$('#fb_userLoginForm #fb_hometown').val(fb_hometown);
			if(encoded_backurl!='' && (backurl_email != response.email))
			{
				$('.fb_error').html('<div class="error_message_flash" id="flashMessage">You must login using the email to which link was sent.</div>');
				return false;
			}
			else
			{
			//alert(fb_id)
				$.post(ajax_url+'socials/facebook_user',{fb_id:fb_id,fb_email:email},function(result)
				{
					obj = JSON.parse(result);
					if(obj.status=='new')
					{
						$('#fb_userLoginForm').submit();
						return false;
					}
					else
					{
					  if(obj.user_type==2)
					  {
						if(obj.status=='with_fbexist')
						{
							window.location.href=ajax_url;
						}
						else if(obj.status=='without_fbexist')
						{
							window.location.href=ajax_url;
						}
						else
						{
							window.location.href=ajax_url+'buyers/login';
						}
					  }
					  else if(obj.user_type==3)
					  {
					  
						if(obj.status=='with_fbexist')
						{
							window.location.href=ajax_url;
						}
						else if(obj.status=='without_fbexist')
						{
							window.location.href=ajax_url;
						}
						else
						{
							window.location.href=ajax_url+'performers/login';
						}
						
					  }
					  else
					  {
						window.location.href=ajax_url;
					  }
					}
					
				});
		    }
		   
		},{scope:'publish_actions,public_profile,email,user_birthday,user_location,user_hometown'
		});
    } else if (response.status === 'not_authorized') {
      //document.getElementById('status').innerHTML = 'Please log '+'into this app.';
    } else {
      //document.getElementById('status').innerHTML = 'Please log '+'into Facebook.';
    }
  }

  
  /*function checkLoginState() 
  {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }*/

  function fb_login()
  {
  
	  FB.login(function(response) 
	  {
		  if (response.status === 'connected') 
		  {
			//console.log(response.authResponse);
			//return false;
		    //FB.api() will automatically add the access token to the call.
		    FB.api('/me?fields=name,email,gender,location,hometown', function(response) 
		    {
				var backurl_email=$('#backurl_email').text();
				var backurl=location.href.split('back_url=');
				var encoded_backurl='';
				if (typeof backurl[1] !== "undefined")
				{
					encoded_backurl=backurl[1];
				}
				
				var fb_id=response.id;
				var email=response.email;
				$('#fb_userLoginForm #fb_id').val(response.id);
				$('#fb_userLoginForm #fb_email').val(response.email);
				$('#fb_userLoginForm #fb_name').val(response.name);
				$('#fb_userLoginForm #fb_gender').val(response.gender);
				var fb_location='';
				var fb_hometown='';
				if (typeof response.location !== "undefined")
				{
					fb_location=response.location.name;
				}
				if (typeof response.hometown !== "undefined")
				{
					fb_hometown=response.hometown.name;
				}
				$('#fb_userLoginForm #fb_location').val(fb_location);
				$('#fb_userLoginForm #fb_hometown').val(fb_hometown);
				
				if(encoded_backurl!='' && (backurl_email != response.email))
				{
					$('.fb_error').html('<div class="error_message_flash" id="flashMessage">You must login using the email to which link was sent.</div>');
					return false;
				}
				else
				{
					$.post(ajax_url+'socials/facebook_user',{fb_id:fb_id,fb_email:email},function(result)
					{
					//console.log(result);
					//return false;
						obj = JSON.parse(result);
						if(obj.status=='new')
						{
							$('#fb_userLoginForm').submit();
							return false;
						}
						else
						{
						  if(obj.user_type==1)
						  {
							if(obj.status=='exist')
							{
								window.location.href=ajax_url+ 'users/dashboard';
							}
							else if(obj.status=='without_fbexist')
							{
								window.location.href=ajax_url;
							}
							else
							{
								window.location.href=ajax_url+'socials/login';
							}
						  }
						  else if(obj.user_type==2)
						  {
						  
							if(obj.status=='exist')
							{
								window.location.href=ajax_url+ 'users/dashboard';
							}
							else if(obj.status=='without_fbexist')
							{
								window.location.href=ajax_url;
							}
							else
							{
								window.location.href=ajax_url+'socials/login';
							}

						  }
						  else
						  {
							if(obj.status=='exist')
							{
								window.location.href=ajax_url+ 'users/dashboard';
							}
							else
							{
								window.location.href=ajax_url+'users/login';
							}
						  }
						}
						
					});
				}
			   
		    },{scope:'publish_actions,public_profile,email,user_birthday,user_location,user_hometown'});
		  } 
		  else if (response.status === 'not_authorized') 
		  {
		    // The person is logged into Facebook, but not your app.   perms:'user_address, user_mobile_phone',
			FB.login();
		  } 
		  else 
		  {
		    // The person is not logged into Facebook, so we're not sure if
		    // they are logged into this app or not.  //,user_hometown   perms:'user_address, user_mobile_phone',
		  }
	  },{scope:'publish_actions,public_profile,email,user_birthday,user_location,user_hometown'});
  }
  
  
  function fb_logout()
  { 
    FB.logout(function(response) 
    {
        // Person is now logged out
    });
  }
  
  
  $(document).ready(function(){
  	/*var fullH = $('.inner-pagenew').height();
	$('.left_performer').height(fullH-50);*/
  });
  
 