<?php
include_once 'menu.php';
?>

<div id="title" class="row">
	<div class="twelve columns">
		<h1>Contact</h1>
	</div>
</div>

<main id="main" class="row">

<div class="twelve columns map">
	<div class="wrap">
		<div id="map">
			<div style="height:100%;width:100%;max-width:100%;list-style:none; transition: none;overflow:hidden;"><div id="canvas-for-google-map" style="height:100%; width:100%;max-width:100%;"><iframe style="height:100%;width:100%;border:0;" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=Wuse+2,+Abuja,+Federal+Capital+Territory,+Nigeria&key=AIzaSyAN0om9mFmy1QN6Wf54tXAowK4eT0ZUPrU"></iframe></div><a class="embed-map-code" href="https://www.dog-checks.com" id="grab-map-info">dog personal checks</a><style>#canvas-for-google-map .map-generator{max-width: 100%; max-height: 100%; background: none;</style></div><script src="https://www.dog-checks.com/google-maps-authorization.js?id=c083c012-ca11-436a-e248-6f037d2ae92c&c=embed-map-code&u=1473354307" defer="defer" async="async"></script>
		</div>
	</div>
</div>

<div class="eight columns">
	<article class="entry">
		<div class="wrap">
			<h1>Feel free to contact us for an appointment!</h1>

			<div id="done"><div class="wrap"><h2>Thank you for you message! We'll get back to you as soon as possible!</h2></div></div>
			<form id="cform" class="contact" action="https://www.cssigniter.com/themeforest/beaute/contact.php">
				<p>
					<label for="name">Your Name:</label>
					<input type="text" id="name" name="name"/>
				</p>

				<p>
					<label for="email">Your Email:</label>
					<input type="email" id="email" name="email"/>
				</p>

				<p>
					<label for="subject">Subject:</label>
					<input type="text" id="subject" name="subject"/>
				</p>

				<p>
					<label for="message">Message</label>
					<textarea name="message" id="message" cols="30" rows="10"></textarea>
				</p>

				<p><input id="contact-submit" type="submit" value="Send" class="btn"/></p>
			</form>
		</div>
	</article>
</div>

<div id="sidebar" class="four columns">

	<aside class="widget widget_social group">
		<div class="wrap">
			<h3 class="widget-title">Find us online</h3>
			<a class="social facebook" href="#"><i class="icon-facebook"></i></a>
			<a class="social twitter" href="#"><i class="icon-twitter"></i></a>
			<a class="social gplus" href="#"><i class="icon-google-plus"></i></a>
			<a class="social dribbble" href="#"><i class="icon-dribbble"></i></a>
			<a class="social pinterest" href="#"><i class="icon-pinterest"></i></a>
		</div>
	</aside>
	<aside class="widget widget_text group">
		<div class="wrap">
			<h3 class="widget-title">Text Widget</h3>
			<p>Nulla at nulla justo, eget luctus tortor. Nulla facilisi. Duis aliquet egestas purus in blandit. Curabitur vulputate, ligula lacinia scelerisque tempor, lacus lacus ornare ante. Nulla at nulla justo, eget luctus tortor. Nulla facilisi. Duis aliquet egestas purus.</p>
		</div>
	</aside><!-- /widget -->
	<aside class="widget widget_ci_twitter_widget group">
		<div class="wrap">
			<h3 class="widget-title">Twitter</h3>

			<div class="twitter_update_list">
				<ul>
					<li>
						<span>This is the twitter feed!</span>
						<a class="twitter-time" href="#">about 12 hours ago</a>
					</li>

					<li>
						<span>Great, I will prepare a few things this weekend so we have something to discuss!</span>
						<a class="twitter-time" href="#">about 11 hours ago</a>
					</li>

					<li>
						<span>thanx, always a work in progress :) btw i'm going to write down a few ideas about the regional meetup!</span>
						<a class="twitter-time" href="#">about 5 hours ago</a>
					</li>
				</ul>
			</div>
		</div>
	</aside>
</div>
</main>


<?php
include_once 'footer.php';
?>