<nav role="navigation" class="site_navigation main_navigation">

	<div id="header_project_links" class="clearfix">

		<span class="nav_project_links border_left va_text clearfix" id="navigation_fund_project">
			<span class="va_text_inner">
				<a href="<?php echo gb_get_deals_link() ?>"><?php gb_e('Find') ?></a>
				<span class="menu_sub_desc"><?php gb_e('some projects') ?></span>
			</span>
		</span>

		<span class="nav_project_links border_left va_text clearfix" id="navigation_create_project">
			<span class="va_text_inner">
				<?php if ( !is_user_logged_in() ): ?>
					<a href="<?php echo add_query_arg( array( 'redirect_to' => gb_get_deal_submission_url() ), gb_get_account_register_url() ) ?>"><?php gb_e('Create') ?></a>
				<?php else: ?>
					<a href="<?php gb_deal_submission_url() ?>"><?php gb_e('Create') ?></a>
				<?php endif ?>
				<span class="menu_sub_desc"><?php gb_e('your project') ?></span>
			</span>
		</span>

	</div>

	<div class="navbar_search_wrap border_left clearfix">
		<form class="navbar_search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ) ?>" role="search">
			<input type="hidden" name="product_search" value="1">
			<input type="text" class="search_query" name="s" placeholder="<?php gb_e('Project Search') ?>">
		</form>
	</div><!--  .va_text -->

	<?php
	// Right menu
	get_template_part( 'section/header-navigation', 'user' ); ?>

</nav><!-- .site_navigation .main_navigation -->