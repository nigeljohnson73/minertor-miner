/*
			 _ ____
  __ _ _ __ (_) ___|_   _____
 / _` | '_ \| \___ \ \ / / __|
| (_| | |_) | |___) \ V / (__
 \__,_| .__/|_|____/ \_/ \___|
	  |_|
 */
app.service('apiSvc', ["$http", "$timeout", "$interval", function ($http, $timeout, $interval) {
	apiSvc = this; // cuz "this" changes later
	apiSvc._queue = []; // holds the API call queue

	/***************************************************************************
	 * Internal call to perform a check to see if an API call is waiting to go
	 */
	apiSvc._queueTick = function () {
		call = apiSvc._queue.shift();
		if (call) {
			//logger("apiSvc._queueTick(): Processing queue item", "dbg");
			// If we have a call in the queue, start processing the queue
			apiSvc.queue_processing = true;
			// exec = null;
			// if (call.method == "local") { exec = apiSvc.callLocal; }
			// else if (call.method == "public") { exec = apiSvc.callPublic; }

			//			if (exec) {
			apiSvc.call(call.api, call.data, function (data) {
				// here we have the response from the server, so process it as
				// expected
				call.notify(data);

				// Give the server a cooldown, then check for the next call
				$timeout(apiSvc._queueTick, 200);
			}, call.exludelog);
			// } else {
			// 	logger("apiSvc._queueTick(): Unknown API method: '" + call.method + "'", "err");
			// }
		} else {
			//logger("apiSvc._queueTick(): Queue is empty", "dbg");
			apiSvc.queue_processing = false;
		}
	};

	/***************************************************************************
	 * Internal call to perform a start the queue check if required
	 */
	apiSvc._queueCheck = function () {
		// If we finished processing, then just check we don't need resstarting
		// again
		if (!apiSvc.queue_processing) {
			//logger("apiSvc._queueCheck(): Starting queue processor", "dbg");
			apiSvc._queueTick();
		}
	};
	$interval(apiSvc._queueCheck, 1000);

	/***************************************************************************
	 * Queue an API call to the next available slot. Use this is your call is
	 * not urgent
	 */
	apiSvc.queue = function (api, data, notify, excludelog) {
		apiSvc._queue.push({
			method: "local",
			api: api,
			data: data,
			notify: notify,
			excludelog: excludelog
		});
	};

	/***************************************************************************
	 * Call an API immediately. Pass a data object to the API, the callback to
	 * notify when the API returns. data can be an object or a function that
	 * returns an object. excludelog is used to hide paramters in the txdata in
	 * the logging process
	 */
	apiSvc.call = function (api, data, notify, excludelog) {
		// If data is a function, call it to get the data
		data = (typeof data == "function") ? data() : data;

		// Convert any passed in excludelog into an array we can search through
		excludelog = (excludelog == undefined) ? ([]) : (Array.isArray(excludelog) ? excludelog : [excludelog]);

		// If there is any core data, like tokens, add them here somehow
		txdata = {};
		logtxdata = {};

		// now move the user data in
		for (var attrname in data) {
			txdata[attrname] = data[attrname];
			logtxdata[attrname] = (excludelog.indexOf(attrname) == -1) ? (data[attrname]) : ("********");
		}

		logger("apiSvc.call('" + api + "')", "dbg");
		logger(logtxdata, "dbg");

		// Send it all over to the server
		$http({
			method: "POST",
			url: api,
			//url : '/api/' + api,
			data: $.param(txdata),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function (data) {
			logger("apiSvc.call('" + api + "'): success", "dbg");
			// console.log(data);
			ldata = {};
			if (isJson(data.data)) {
				// http response object returned, strip out the server response
				ldata = data.data;
				//datastr = (ldata.console + "").trim();
				//ldata.console = (datastr.length) ? (datastr.split(/\r\n|\r|\n/)) : "";
			} else {
				logger("apiSvc.call('" + api + "'): malformed response", "wrn");
				// Any returned text in the console where you would expect some explanation
				datastr = (data.data + "").trim();
				ldata.console = (datastr.length) ? (datastr.split(/\r\n|\r|\n/)) : "";
				ldata.success = false;
				ldata.status = "FAIL";
				ldata.message = "";
				logger(ldata, "wrn");
			}

			if (typeof notify == "function") {
				logger("apiSvc.call('" + api + "'): calling notifier", "dbg");
				notify(ldata);
			}
		}, function (data) {
			logger("apiSvc.call('" + api + "'): fail", "dbg");
			// console.log(data);
			ldata = {};
			if (data.status == -1) {
				logger("apiSvc.call('" + api + "'): HTTP fail text 'Cannot connect to API'", "wrn"); // No internet or CORS!!!!
			} else {
				logger("apiSvc.call('" + api + "'): HTTP fail code " + data.status, "wrn");
				logger("apiSvc.call('" + api + "'): HTTP fail text '" + data.statusText + "'", "wrn");
			}

			if (data.data == null) {
				data.data = "";
			}
			datastr = (data.data + "").trim();
			ldata.console = (datastr.length) ? (datastr.split(/\r\n|\r|\n/)) : "";
			ldata.success = false;
			ldata.status = "FAIL";
			ldata.message = "";
			logger(ldata, "wrn");

			if (typeof notify == "function") {
				logger("apiSvc.call('" + api + "'): calling notifier", "dbg");
				notify(ldata);
			}
		});
	};
}]);
