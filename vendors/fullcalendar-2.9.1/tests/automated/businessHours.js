// most other businessHours tests are in background-events.js

describe('businessHours', function() {
	var options;

	beforeEach(function() {
		options = {
			defaultDate: '2014-11-25',
			defaultView: 'month',
			businessHours: true
		};
		affix('#cal');
	});


	it('doesn\'t break when starting out in a larger month time range', function() {
		$('#cal').fullCalendar(options); // start out in the month range
		$('#cal').fullCalendar('changeView', 'agendaWeek');
		$('#cal').fullCalendar('next'); // move out of the original month range...
		$('#cal').fullCalendar('next'); // ... out. should render correctly.

		// whole days
		expect($('.fc-day-grid .fc-nonbusiness').length).toBe(2); // each multi-day stretch is one element

		// timed area
		expect(isTimeGridNonBusinessSegsRendered([
			// sun
			{ start: '2014-12-07T00:00', end: '2014-12-08T00:00' },
			// mon
			{ start: '2014-12-08T00:00', end: '2014-12-08T09:00' },
			{ start: '2014-12-08T17:00', end: '2014-12-09T00:00' },
			// tue
			{ start: '2014-12-09T00:00', end: '2014-12-09T09:00' },
			{ start: '2014-12-09T17:00', end: '2014-12-10T00:00' },
			// wed
			{ start: '2014-12-10T00:00', end: '2014-12-10T09:00' },
			{ start: '2014-12-10T17:00', end: '2014-12-11T00:00' },
			// thu
			{ start: '2014-12-11T00:00', end: '2014-12-11T09:00' },
			{ start: '2014-12-11T17:00', end: '2014-12-12T00:00' },
			// fri
			{ start: '2014-12-12T00:00', end: '2014-12-12T09:00' },
			{ start: '2014-12-12T17:00', end: '2014-12-13T00:00' },
			// sat
			{ start: '2014-12-13T00:00', end: '2014-12-14T00:00' }
		])).toBe(true);
	});


	describe('when used as a dynamic option', function() {
		[ 'agendaWeek', 'month' ].forEach(function(viewName) {

			it('allows dynamic turning on', function() {
				$('#cal').fullCalendar({
					defaultView: viewName,
					businessHours: false
				});
				var rootEl = $('.fc-view > *:first');
				expect(rootEl.length).toBe(1);

				expect(queryNonBusinessSegs().length).toBe(0);
				$('#cal').fullCalendar('option', 'businessHours', true);
				expect(queryNonBusinessSegs().length).toBeGreaterThan(0);

				expect($('.fc-view > *:first')[0]).toBe(rootEl[0]); // same element. didn't completely rerender
			});

			it('allows dynamic turning off', function() {
				$('#cal').fullCalendar({
					defaultView: viewName,
					businessHours: true
				});
				var rootEl = $('.fc-view > *:first');
				expect(rootEl.length).toBe(1);

				expect(queryNonBusinessSegs().length).toBeGreaterThan(0);
				$('#cal').fullCalendar('option', 'businessHours', false);
				expect(queryNonBusinessSegs().length).toBe(0);

				expect($('.fc-view > *:first')[0]).toBe(rootEl[0]); // same element. didn't completely rerender
			});
		});
	});


	describe('for multiple day-of-week definitions', function() {

		it('rendes two day-of-week groups', function() {
			$('#cal').fullCalendar({
				defaultDate: '2014-12-07',
				defaultView: 'agendaWeek',
				businessHours: [
					{
						dow: [ 1, 2, 3 ], // mon, tue, wed
						start: '08:00',
						end: '18:00'
					},
					{
						dow: [ 4, 5 ], // thu, fri
						start: '10:00',
						end: '16:00'
					}
				]
			});

			// timed area
			expect(isTimeGridNonBusinessSegsRendered([
				// sun
				{ start: '2014-12-07T00:00', end: '2014-12-08T00:00' },
				// mon
				{ start: '2014-12-08T00:00', end: '2014-12-08T08:00' },
				{ start: '2014-12-08T18:00', end: '2014-12-09T00:00' },
				// tue
				{ start: '2014-12-09T00:00', end: '2014-12-09T08:00' },
				{ start: '2014-12-09T18:00', end: '2014-12-10T00:00' },
				// wed
				{ start: '2014-12-10T00:00', end: '2014-12-10T08:00' },
				{ start: '2014-12-10T18:00', end: '2014-12-11T00:00' },
				// thu
				{ start: '2014-12-11T00:00', end: '2014-12-11T10:00' },
				{ start: '2014-12-11T16:00', end: '2014-12-12T00:00' },
				// fri
				{ start: '2014-12-12T00:00', end: '2014-12-12T10:00' },
				{ start: '2014-12-12T16:00', end: '2014-12-13T00:00' },
				// sat
				{ start: '2014-12-13T00:00', end: '2014-12-14T00:00' }
			])).toBe(true);
		});

		it('wont\'t process businessHour items that omit dow', function() {
			$('#cal').fullCalendar({
				defaultDate: '2014-12-07',
				defaultView: 'agendaWeek',
				businessHours: [
					{
						// invalid
						start: '08:00',
						end: '18:00'
					},
					{
						dow: [ 4, 5 ], // thu, fri
						start: '10:00',
						end: '16:00'
					}
				]
			});

			// timed area
			expect(isTimeGridNonBusinessSegsRendered([
				// sun
				{ start: '2014-12-07T00:00', end: '2014-12-08T00:00' },
				// mon
				{ start: '2014-12-08T00:00', end: '2014-12-09T00:00' },
				// tue
				{ start: '2014-12-09T00:00', end: '2014-12-10T00:00' },
				// wed
				{ start: '2014-12-10T00:00', end: '2014-12-11T00:00' },
				// thu
				{ start: '2014-12-11T00:00', end: '2014-12-11T10:00' },
				{ start: '2014-12-11T16:00', end: '2014-12-12T00:00' },
				// fri
				{ start: '2014-12-12T00:00', end: '2014-12-12T10:00' },
				{ start: '2014-12-12T16:00', end: '2014-12-13T00:00' },
				// sat
				{ start: '2014-12-13T00:00', end: '2014-12-14T00:00' }
			])).toBe(true);
		});
	});


	it('will grey-out a totally non-business-hour view', function() {
		$('#cal').fullCalendar({
			defaultDate: '2016-07-23', // sat
			defaultView: 'agendaDay',
			businessHours: true
		});

		// timed area
		expect(isTimeGridNonBusinessSegsRendered([
			{ start: '2016-07-23T00:00', end: '2016-07-24T00:00' }
		])).toBe(true);
	});


	function queryNonBusinessSegs() {
		return $('.fc-nonbusiness');
	}

	/* inspired by other proj...
	------------------------------------------------------------------------------------------------------------------*/

	function isTimeGridNonBusinessSegsRendered(segs) {
		return doElsMatchSegs($('.fc-time-grid .fc-nonbusiness'), segs, getTimeGridRect);
	}

	function getTimeGridRect(start, end) {
		var obj;
		if (typeof start === 'object') {
			obj = start;
			start = obj.start;
			end = obj.end;
		}

		start = $.fullCalendar.moment.parseZone(start);
		end = $.fullCalendar.moment.parseZone(end);

		var startTime = start.time();
		var endTime;
		if (end.isSame(start, 'day')) {
			endTime = end.time();
		}
		else if (end < start) {
			endTime = startTime;
		}
		else {
			endTime = moment.duration({ hours: 24 });
		}

		var dayEls = getTimeGridDayEls(start);
		var dayRect = getBoundingRect(dayEls);
		return {
			left: dayRect.left,
			right: dayRect.right,
			top: getTimeGridTop(startTime),
			bottom: getTimeGridTop(endTime)
		};
	}

	/* copied from other proj...
	------------------------------------------------------------------------------------------------------------------*/

	function getTimeGridTop(targetTime) {
		var i, j, len, prevSlotEl, prevSlotTime, slotEl, slotEls, slotMsDuration, slotTime, topBorderWidth;
		targetTime = moment.duration(targetTime);
		slotEls = getTimeGridSlotEls(targetTime);
		topBorderWidth = 1;
		if (slotEls.length === 1) {
			return slotEls.eq(0).offset().top + topBorderWidth;
		}
		slotEls = $('.fc-time-grid .fc-slats tr[data-time]');
		slotTime = null;
		prevSlotTime = null;
		for (i = j = 0, len = slotEls.length; j < len; i = ++j) {
			slotEl = slotEls[i];
			slotEl = $(slotEl);
			prevSlotTime = slotTime;
			slotTime = moment.duration(slotEl.data('time'));
			if (targetTime < slotTime) {
				if (!prevSlotTime) {
					return slotEl.offset().top + topBorderWidth;
				}
				else {
					prevSlotEl = slotEls.eq(i - 1);
					return prevSlotEl.offset().top + topBorderWidth +
						prevSlotEl.outerHeight() * ((targetTime - prevSlotTime) / (slotTime - prevSlotTime));
				}
			}
		}
		slotMsDuration = slotTime - prevSlotTime;
		return slotEl.offset().top + topBorderWidth +
			slotEl.outerHeight() * Math.min(1, (targetTime - slotTime) / slotMsDuration);
	}

	function getTimeGridDayEls(date) {
		date = $.fullCalendar.moment.parseZone(date);
		return $('.fc-time-grid .fc-day[data-date="' + date.format('YYYY-MM-DD') + '"]');
	}

	function getTimeGridSlotEls(timeDuration) {
		timeDuration = moment.duration(timeDuration);
		var date = $.fullCalendar.moment.utc('2016-01-01').time(timeDuration);
		if (date.date() == 1) { // ensure no time overflow/underflow
			return $('.fc-time-grid .fc-slats tr[data-time="' + date.format('HH:mm:ss') + '"]');
		}
		else {
			return $();
		}
	}

});