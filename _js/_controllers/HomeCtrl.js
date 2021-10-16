app.controller('HomeCtrl', ["$scope", "$timeout", "$interval", "$sce", "apiSvc", function ($scope, $timeout, $interval, $sce, apiSvc) {
	$scope.server = null;

	$scope.getSummary = function () {
		logger("HomeCtrl::getSummary() called", "dbg");
		apiSvc.call("/api/ping", {}, function (data) {
			logger("HomeCtrl::getSummary() API returned", "dbg");
			logger(data, "inf");
			$scope.server = data.server;

			if (data.success) {
				logger("HomeCtrl::getSummary() success", "dbg");

			} else {
				logger("HomeCtrl::getSummary() failed", "dbg");
			}
			$scope.reason = $sce.trustAsHtml(data.reason);

			if (data.message.length) {
				toast(data.message);
			}
			//$scope.submitting = false;
		});
	};

	// Start the calling, but after a startup grace period
	$scope.load_user_api_call = $timeout($scope.getSummary, 100);
	$scope.load_user_api_interval = $interval($scope.getSummary, 10000);

}]);
