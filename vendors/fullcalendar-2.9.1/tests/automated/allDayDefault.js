
describe('allDayDefault', function() {

	beforeEach(function() {
		affix('#cal');
	});

	describe('when undefined', function() {

		it('guesses false if T in ISO8601 start date', function() {
			$('#cal').fullCalendar({
				events: [
					{
						id: '1',
						start: '2014-05-01T06:00:00'
					}
				]
			});
			var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
			expect(eventObj.allDay).toEqual(false);
		});

		it('guesses false if T in ISO8601 end date', function() {
			$('#cal').fullCalendar({
				events: [
					{
						id: '1',
						start: '2014-05-01',
						end: '2014-05-01T08:00:00'
					}
				]
			});
			var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
			expect(eventObj.allDay).toEqual(false);
		});

		it('guesses true if ISO8601 start date with no time and unspecified end date', function() {
			$('#cal').fullCalendar({
				events: [
					{
						id: '1',
						start: '2014-05-01'
					}
				]
			});
			var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
			expect(eventObj.allDay).toEqual(true);
		});

		it('guesses true if ISO8601 start and end date with no times', function() {
			$('#cal').fullCalendar({
				events: [
					{
						id: '1',
						start: '2014-05-01',
						end: '2014-05-03'
					}
				]
			});
			var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
			expect(eventObj.allDay).toEqual(true);
		});

		it('guesses false if start is a unix timestamp (which implies it has a time)', function() {
			$('#cal').fullCalendar({
				events: [
					{
						id: '1',
						start: 1398902400000,
						end: '2014-05-03'
					}
				]
			});
			var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
			expect(eventObj.allDay).toEqual(false);
		});

		it('guesses false if end is a unix timestamp (which implies it has a time)', function() {
			$('#cal').fullCalendar({
				events: [
					{
						id: '1',
						start: '2014-05-01',
						end: 1399075200000
					}
				]
			});
			var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
			expect(eventObj.allDay).toEqual(false);
		});

	});

	describe('when specified', function() {

		it('has an effect when an event\'s allDay is not specified', function() {
			$('#cal').fullCalendar({
				allDayDefault: false,
				events: [
					{
						id: '1',
						start: '2014-05-01'
					}
				]
			});
			var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
			expect(eventObj.allDay).toEqual(false);
		});

		it('has no effect when an event\'s allDay is specified', function() {
			$('#cal').fullCalendar({
				allDayDefault: false,
				events: [
					{
						id: '1',
						start: '2014-05-01T00:00:00',
						allDay: true
					}
				]
			});
			var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
			expect(eventObj.allDay).toEqual(true);
		});

	});

});

describe('source.allDayDefault', function() {

	beforeEach(function() {
		affix('#cal');
	});

	it('has an effect when an event\'s allDay is not specified', function() {
		$('#cal').fullCalendar({
			eventSources: [
				{
					allDayDefault: false,
					events: [
						{
							id: '1',
							start: '2014-05-01'
						}
					]
				}
			]
		});
		var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
		expect(eventObj.allDay).toEqual(false);
	});

	it('a true value can override the global allDayDefault', function() {
		$('#cal').fullCalendar({
			allDayDefault: false,
			eventSources: [
				{
					allDayDefault: true,
					events: [
						{
							id: '1',
							start: '2014-05-01T06:00:00'
						}
					]
				}
			]
		});
		var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
		expect(eventObj.allDay).toEqual(true);
	});

	it('a false value can override the global allDayDefault', function() {
		$('#cal').fullCalendar({
			allDayDefault: true,
			eventSources: [
				{
					allDayDefault: false,
					events: [
						{
							id: '1',
							start: '2014-05-01'
						}
					]
				}
			]
		});
		var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
		expect(eventObj.allDay).toEqual(false);
	});

	it('has no effect when an event\'s allDay is specified', function() {
		$('#cal').fullCalendar({
			eventSources: [
				{
					allDayDefault: true,
					events: [
						{
							id: '1',
							start: '2014-05-01',
							allDay: false
						}
					]
				}
			]
		});
		var eventObj = $('#cal').fullCalendar('clientEvents', '1')[0];
		expect(eventObj.allDay).toEqual(false);
	});

});
