<?php $keyword = isset( $_GET['q'] ) ? $_GET['q'] : ''; ?>
<div class="container">
	<form id="Search" class="pos-rel" action="<?php print site_url( 'serp' ); ?>">
		<span class="sr-only" id="SearchInput">Custom Google Search</span>
		<input type="text" id="q" name="q" value="<?php print esc_attr( $keyword ); ?>" aria-labelledby="SearchInput" placeholder="Search" class="search-textfield height-50 border-0 p-x-sm w-100" />
		<button type="submit" class="pos-abs gsc-search-button top-0 width-50 height-50 border-0 bg-transparent">
			<span class="ca-gov-icon-search font-size-30 color-gray" aria-hidden="true" ></span>
			<span class="sr-only">Submit</span>
		</button>
		<div class="width-50 height-50 close-search-btn">
			<!-- Some Google styles add an 'x' background image when button has 'gsc-clear-button' in the class -->
			<button class="close-search gsc-clear-button width-50 height-50 border-0 bg-transparent pos-rel" type="reset" tab-index="-1">
				<span class="sr-only">Close Search</span>
				<span class="ca-gov-icon-close-mark" aria-hidden="true"></span>
			</button>
		</div>
	</form> 
</div>
