<?php include_once(__DIR__ . "/_header.php") ?>
<div class="container-fluid text-center" data-ng-controller="HomeCtrl">
	<img src="/gfx/ajax-loader-bar.gif" alt="submitting" data-ng-show="loading" />
	<div data-ng-show="!loading">

		<h1>Coming soon</h1>
		<!-- Web server stats -->
		<div class='data-container server-stats' data-ng-show='server'>
			<div class='data-section server-stats'>
				<div class='data-row'>
					<div class='banner'>Server</div>
				</div>
				<div class='data-row'>
					<div class='label'>Temperature:</div>
					<div class='value' highlight-on-change='{{server.temperature}}'>{{server.temperature | number:2}} {{server.temperature_unit}}</div>
				</div>
				<div class='data-row'>
					<div class='label'>CPU load:</div>
					<div class='value' highlight-on-change='{{server.cpu_load}}'>{{server.cpu_load | number:2}}%</div>
				</div>
				<div class='data-row'>
					<div class='label'>RAM load:</div>
					<div class='value' highlight-on-change='{{server.mem_load}}'>{{server.mem_load | number:2}}%</div>
				</div>
				<div class='data-row'>
					<div class='label'>HDD load:</div>
					<div class='value' highlight-on-change='{{server.cpu_load}}'>{{server.sd_load | number:2}}%</div>
				</div>
				<div class='data-row'>
					<div class='label'>CPU Frequency:</div>
					<div class='value' highlight-on-change='{{server.frequency_cpu}}'>{{server.frequency_cpu | number:0}} {{server.frequency_unit}}</div>
				</div>
				<div class='data-row'>
					<div class='label'>VPN:</div>
					<div class='value' highlight-on-change='{{server.vpn_connection}}'>{{server.vpn_connection}}</div>
				</div>
			</div>
		</div>

	</div>
	<!-- loading -->
</div>
<!-- container -->

<?php include_once(__DIR__ . "/_footer.php") ?>