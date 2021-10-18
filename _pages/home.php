<?php include_once(__DIR__ . "/_header.php") ?>

<div class="text-center" data-ng-controller="HomeCtrl">
	<img src="/gfx/ajax-loader-bar.gif" alt="submitting" data-ng-show="loading" />
	<div data-ng-show="!loading">

		<div class="container-fluid d-none d-sm-flex justify-content-evenly flex-wrap">
			<div class='data-container server-stats' data-ng-show='server'>
				<div class='data-section server-stats' data-ng-show="server.wpa_ssid || server.ap_ssid">
					<div class='data-row'>
						<div class='banner'>Wifi</div>
					</div>
					<div class='data-row' data-ng-show="server.wpa_ssid">
						<div class='label'>Upstream SSID:</div>
						<div class='value'>{{server.wpa_ssid}}</div>
					</div>
					<div class='data-row' data-ng-show="server.wpa_ssid">
						<div class='label'>Passphrase:</div>
						<div class='value'>{{server.wpa_pass}}</div>
					</div>
					<div class='data-row' data-ng-show="server.ap_ssid">
						<div class='label'>Access Point SSID:</div>
						<div class='value'>{{server.ap_ssid}}</div>
					</div>
					<div class='data-row' data-ng-show="server.ap_ssid">
						<div class='label'>Passphrase:</div>
						<div class='value'>{{server.ap_pass}}</div>
					</div>
				</div>
			</div>

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
					<!-- <div class='data-row'>
						<div class='label'>CPU Frequency:</div>
						<div class='value' highlight-on-change='{{server.frequency_cpu}}'>{{server.frequency_cpu | number:0}} {{server.frequency_unit}}</div>
					</div> -->
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
			<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="server-tab" data-bs-toggle="tab" data-bs-target="#server" type="button" role="tab" aria-controls="server" aria-selected="true">Server</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="alarms-tab" data-bs-toggle="tab" data-bs-target="#alarms" type="button" role="tab" aria-controls="alarms" aria-selected="false">Alarms</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="miners-tab" data-bs-toggle="tab" data-bs-target="#miners" type="button" role="tab" aria-controls="miners" aria-selected="false">Miners</button>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="server" role="tabpanel" aria-labelledby="server-tab">
					<br />
					<div class='data-container server-stats' data-ng-show='server'>
						<div class='data-section server-stats' data-ng-show="server.wpa_ssid || server.ap_ssid">
							<div class='data-row'>
								<div class='banner'>Wifi</div>
							</div>
							<div class='data-row' data-ng-show="server.wpa_ssid">
								<div class='label'>Upstream SSID:</div>
								<div class='value'>{{server.wpa_ssid}}</div>
							</div>
							<div class='data-row' data-ng-show="server.wpa_ssid">
								<div class='label'>Passphrase:</div>
								<div class='value'>{{server.wpa_pass}}</div>
							</div>
							<div class='data-row' data-ng-show="server.ap_ssid">
								<div class='label'>Access Point SSID:</div>
								<div class='value'>{{server.ap_ssid}}</div>
							</div>
							<div class='data-row' data-ng-show="server.ap_ssid">
								<div class='label'>Passphrase:</div>
								<div class='value'>{{server.ap_pass}}</div>
							</div>
						</div>
					</div>

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
							<!-- <div class='data-row'>
								<div class='label'>CPU Frequency:</div>
								<div class='value' highlight-on-change='{{server.frequency_cpu}}'>{{server.frequency_cpu | number:0}} {{server.frequency_unit}}</div>
							</div> -->
							<div class='data-row'>
								<div class='label'>VPN:</div>
								<div class='value' highlight-on-change='{{server.vpn_connection}}'>{{server.vpn_connection}}</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="alarms" role="tabpanel" aria-labelledby="alarms-tab">
					<br />
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
				</div>
				<div class="tab-pane fade" id="miners" role="tabpanel" aria-labelledby="miners-tab">
					<br />
					<h1>Coming soon</h1>
					<button type="button" class="btn btn-custom" onclick="location.reload()">Reload</button>
				</div>
			</div>





		</div> <!-- small panel -->
	</div> <!-- loading -->
</div> <!-- container -->

<?php include_once(__DIR__ . "/_footer.php") ?>