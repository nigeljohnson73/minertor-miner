<?php include_once(__DIR__ . "/_header.php") ?>

<div class="container-fluid text-center" data-ng-controller="AboutCtrl">
	<?php

	use Michelf\MarkdownExtra;

	$fn = __DIR__ . "/" . str_replace(".php", "", basename(__FILE__)) . ".md";
	if (file_exists($fn)) {
		$md = file_get_contents($fn);
		$html = MarkdownExtra::defaultTransform($md);
		echo $html;
	} else {
		echo "<h1>No content here - yet</h1>";
	}
	?>
	<div class="about-data">
		<div class="row">
			<div class="col-6 text-end fw-bold">App version:</div>
			<div class="col-6 text-start">{{app_version}}</div>
		</div>
		<div class="row">
			<div class="col-6 text-end fw-bold">App build date:</div>
			<div class="col-6 text-start">{{build_date}}</div>
		</div>
		<div class="row">
			<div class="col-6 text-end fw-bold">API build date:</div>
			<div class="col-6 text-start">{{api_build_date}}</div>
		</div>
	</div>
</div>

<?php include_once(__DIR__ . "/_footer.php") ?>