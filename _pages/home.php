<?php include_once(__DIR__ . "/_header.php") ?>

<div class="text-center" data-ng-controller="HomeCtrl">
	<img src="/gfx/ajax-loader-bar.gif" alt="submitting" data-ng-show="loading" />
	<div data-ng-show="!loading">

		<div class="container-fluid d-none d-sm-block">
			<h1>Coming soon</h1>
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

			<div class='data-container server-alarms' data-ng-show='server'>
				<div class='data-section'>
					<div class='data-row'>
						<div class='banner'>Alarms</div>
					</div>
					<div class='data-row'>
						<div class='label'>VPN:</div>
						<div class='value alarm alarm_{{server.vpn_alarm}}'>{{server.vpn_alarm}}</div>
					</div>
					<div class='data-row'>
						<div class='label'>Throttled:</div>
						<div class='value alarm alarm_{{server.throttled}}'>{{server.throttled}}</div>
					</div>
					<div class='data-row'>
						<div class='label'>Capped:</div>
						<div class='value alarm alarm_{{server.frequency_capped}}'>{{server.frequency_capped}}</div>
					</div>
					<div class='data-row'>
						<div class='label'>Soft temp:</div>
						<div class='value alarm alarm_{{server.soft_temperature_limited}}'>{{server.soft_temperature_limited}}</div>
					</div>
					<div class='data-row'>
						<div class='label'>Under volt:</div>
						<div class='value alarm alarm_{{server.under_voltage}}'>{{server.under_voltage}}</div>
					</div>
				</div>
			</div>
		</div> <!-- big panel -->

		<div class="d-block d-sm-none panel-display">
			<h1>Coming soon</h1>
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

			<div class='data-container server-alarms' data-ng-show='server'>
				<div class='data-section'>
					<div class='data-row'>
						<div class='banner'>Alarms</div>
					</div>
					<div class='data-row'>
						<div class='label'>VPN:</div>
						<div class='value alarm alarm_{{server.vpn_alarm}}'>{{server.vpn_alarm}}</div>
					</div>
					<div class='data-row'>
						<div class='label'>Throttled:</div>
						<div class='value alarm alarm_{{server.throttled}}'>{{server.throttled}}</div>
					</div>
					<div class='data-row'>
						<div class='label'>Capped:</div>
						<div class='value alarm alarm_{{server.frequency_capped}}'>{{server.frequency_capped}}</div>
					</div>
					<div class='data-row'>
						<div class='label'>Soft temp:</div>
						<div class='value alarm alarm_{{server.soft_temperature_limited}}'>{{server.soft_temperature_limited}}</div>
					</div>
					<div class='data-row'>
						<div class='label'>Under volt:</div>
						<div class='value alarm alarm_{{server.under_voltage}}'>{{server.under_voltage}}</div>
					</div>
				</div>
			</div>
			<p>Small panel filler.</p>
			<p>Small panel filler.</p>
			<button type="button" class="btn btn-custom" onclick="location.reload()">Reload</button>
		</div> <!-- small panel -->
	</div> <!-- loading -->
</div> <!-- container -->

<?php include_once(__DIR__ . "/_footer.php") ?>