
/* An abstract class from which other views inherit from
----------------------------------------------------------------------------------------------------------------------*/

var View = FC.View = Class.extend(EmitterMixin, ListenerMixin, {

	type: null, // subclass' view name (string)
	name: null, // deprecated. use `type` instead
	title: null, // the text that will be displayed in the header's title

	calendar: null, // owner Calendar object
	options: null, // hash containing all options. already merged with view-specific-options
	el: null, // the view's containing element. set by Calendar

	displaying: null, // a promise representing the state of rendering. null if no render requested
	isSkeletonRendered: false,
	isEventsRendered: false,

	// range the view is actually displaying (moments)
	start: null,
	end: null, // exclusive

	// range the view is formally responsible for (moments)
	// may be different from start/end. for example, a month view might have 1st-31st, excluding padded dates
	intervalStart: null,
	intervalEnd: null, // exclusive
	intervalDuration: null,
	intervalUnit: null, // name of largest unit being displayed, like "month" or "week"

	isRTL: false,
	isSelected: false, // boolean whether a range of time is user-selected or not
	selectedEvent: null,

	eventOrderSpecs: null, // criteria for ordering events when they have same date/time

	// classNames styled by jqui themes
	widgetHeaderClass: null,
	widgetContentClass: null,
	highlightStateClass: null,

	// for date utils, computed from options
	nextDayThreshold: null,
	isHiddenDayHash: null,

	// now indicator
	isNowIndicatorRendered: null,
	initialNowDate: null, // result first getNow call
	initialNowQueriedMs: null, // ms time the getNow was called
	nowIndicatorTimeoutID: null, // for refresh timing of now indicator
	nowIndicatorIntervalID: null, // "


	constructor: function(calendar, type, options, intervalDuration) {

		this.calendar = calendar;
		this.type = this.name = type; // .name is deprecated
		this.options = options;
		this.intervalDuration = intervalDuration || moment.duration(1, 'day');

		this.nextDayThreshold = moment.duration(this.opt('nextDayThreshold'));
		this.initThemingProps();
		this.initHiddenDays();
		this.isRTL = this.opt('isRTL');

		this.eventOrderSpecs = parseFieldSpecs(this.opt('eventOrder'));

		this.initialize();
	},


	// A good place for subclasses to initialize member variables
	initialize: function() {
		// subclasses can implement
	},


	// Retrieves an option with the given name
	opt: function(name) {
		return this.options[name];
	},


	// Triggers handlers that are view-related. Modifies args before passing to calendar.
	trigger: function(name, thisObj) { // arguments beyond thisObj are passed along
		var calendar = this.calendar;

		return calendar.trigger.apply(
			calendar,
			[name, thisObj || this].concat(
				Array.prototype.slice.call(arguments, 2), // arguments beyond thisObj
				[ this ] // always make the last argument a reference to the view. TODO: deprecate
			)
		);
	},


	/* Dates
	------------------------------------------------------------------------------------------------------------------*/


	// Updates all internal dates to center around the given current unzoned date.
	setDate: function(date) {
		this.setRange(this.computeRange(date));
	},


	// Updates all internal dates for displaying the given unzoned range.
	setRange: function(range) {
		$.extend(this, range); // assigns every property to this object's member variables
		this.updateTitle();
	},


	// Given a single current unzoned date, produce information about what range to display.
	// Subclasses can override. Must return all properties.
	computeRange: function(date) {
		var intervalUnit = computeIntervalUnit(this.intervalDuration);
		var intervalStart = date.clone().startOf(intervalUnit);
		var intervalEnd = intervalStart.clone().add(this.intervalDuration);
		var start, end;

		// normalize the range's time-ambiguity
		if (/year|month|week|day/.test(intervalUnit)) { // whole-days?
			intervalStart.stripTime();
			intervalEnd.stripTime();
		}
		else { // needs to have a time?
			if (!intervalStart.hasTime()) {
				intervalStart = this.calendar.time(0); // give 00:00 time
			}
			if (!intervalEnd.hasTime()) {
				intervalEnd = this.calendar.time(0); // give 00:00 time
			}
		}

		start = intervalStart.clone();
		start = this.skipHiddenDays(start);
		end = intervalEnd.clone();
		end = this.skipHiddenDays(end, -1, true); // exclusively move backwards

		return {
			intervalUnit: intervalUnit,
			intervalStart: intervalStart,
			intervalEnd: intervalEnd,
			start: start,
			end: end
		};
	},


	// Computes the new date when the user hits the prev button, given the current date
	computePrevDate: function(date) {
		return this.massageCurrentDate(
			date.clone().startOf(this.intervalUnit).subtract(this.intervalDuration), -1
		);
	},


	// Computes the new date when the user hits the next button, given the current date
	computeNextDate: function(date) {
		return this.massageCurrentDate(
			date.clone().startOf(this.intervalUnit).add(this.intervalDuration)
		);
	},


	// Given an arbitrarily calculated current date of the calendar, returns a date that is ensured to be completely
	// visible. `direction` is optional and indicates which direction the current date was being
	// incremented or decremented (1 or -1).
	massageCurrentDate: function(date, direction) {
		if (this.intervalDuration.as('days') <= 1) { // if the view displays a single day or smaller
			if (this.isHiddenDay(date)) {
				date = this.skipHiddenDays(date, direction);
				date.startOf('day');
			}
		}

		return date;
	},


	/* Title and Date Formatting
	------------------------------------------------------------------------------------------------------------------*/


	// Sets the view's title property to the most updated computed value
	updateTitle: function() {
		this.title = this.computeTitle();
	},


	// Computes what the title at the top of the calendar should be for this view
	computeTitle: function() {
		return this.formatRange(
			{
				// in case intervalStart/End has a time, make sure timezone is correct
				start: this.calendar.applyTimezone(this.intervalStart),
				end: this.calendar.applyTimezone(this.intervalEnd)
			},
			this.opt('titleFormat') || this.computeTitleFormat(),
			this.opt('titleRangeSeparator')
		);
	},


	// Generates the format string that should be used to generate the title for the current date range.
	// Attempts to compute the most appropriate format if not explicitly specified with `titleFormat`.
	computeTitleFormat: function() {
		if (this.intervalUnit == 'year') {
			return 'YYYY';
		}
		else if (this.intervalUnit == 'month') {
			return this.opt('monthYearFormat'); // like "September 2014"
		}
		else if (this.intervalDuration.as('days') > 1) {
			return 'll'; // multi-day range. shorter, like "Sep 9 - 10 2014"
		}
		else {
			return 'LL'; // one day. longer, like "September 9 2014"
		}
	},


	// Utility for formatting a range. Accepts a range object, formatting string, and optional separator.
	// Displays all-day ranges naturally, with an inclusive end. Takes the current isRTL into account.
	// The timezones of the dates within `range` will be respected.
	formatRange: function(range, formatStr, separator) {
		var end = range.end;

		if (!end.hasTime()) { // all-day?
			end = end.clone().subtract(1); // convert to inclusive. last ms of previous day
		}

		return formatRange(range.start, end, formatStr, separator, this.opt('isRTL'));
	},


	/* Rendering
	------------------------------------------------------------------------------------------------------------------*/


	// Sets the container element that the view should render inside of.
	// Does other DOM-related initializations.
	setElement: function(el) {
		this.el = el;
		this.bindGlobalHandlers();
	},


	// Removes the view's container element from the DOM, clearing any content beforehand.
	// Undoes any other DOM-related attachments.
	removeElement: function() {
		this.clear(); // clears all content

		// clean up the skeleton
		if (this.isSkeletonRendered) {
			this.unrenderSkeleton();
			this.isSkeletonRendered = false;
		}

		this.unbindGlobalHandlers();

		this.el.remove();

		// NOTE: don't null-out this.el in case the View was destroyed within an API callback.
		// We don't null-out the View's other jQuery element references upon destroy,
		//  so we shouldn't kill this.el either.
	},


	// Does everything necessary to display the view centered around the given unzoned date.
	// Does every type of rendering EXCEPT rendering events.
	// Is asychronous and returns a promise.
	display: function(date, explicitScrollState) {
		var _this = this;
		var prevScrollState = null;

		if (explicitScrollState != null && this.displaying) { // don't need prevScrollState if explicitScrollState
			prevScrollState = this.queryScroll();
		}

		this.calendar.freezeContentHeight();

		return syncThen(this.clear(), function() { // clear the content first
			return (
				_this.displaying =
					syncThen(_this.displayView(date), function() { // displayView might return a promise

						// caller of display() wants a specific scroll state?
						if (explicitScrollState != null) {
							// we make an assumption that this is NOT the initial render,
							// and thus don't need forceScroll (is inconveniently asynchronous)
							_this.setScroll(explicitScrollState);
						}
						else {
							_this.forceScroll(_this.computeInitialScroll(prevScrollState));
						}

						_this.calendar.unfreezeContentHeight();
						_this.triggerRender();
					})
			);
		});
	},


	// Does everything necessary to clear the content of the view.
	// Clears dates and events. Does not clear the skeleton.
	// Is asychronous and returns a promise.
	clear: function() {
		var _this = this;
		var displaying = this.displaying;

		if (displaying) { // previously displayed, or in the process of being displayed?
			return syncThen(displaying, function() { // wait for the display to finish
				_this.displaying = null;
				_this.clearEvents();
				return _this.clearView(); // might return a promise. chain it
			});
		}
		else {
			return $.when(); // an immediately-resolved promise
		}
	},


	// Displays the view's non-event content, such as date-related content or anything required by events.
	// Renders the view's non-content skeleton if necessary.
	// Can be asynchronous and return a promise.
	displayView: function(date) {
		if (!this.isSkeletonRendered) {
			this.renderSkeleton();
			this.isSkeletonRendered = true;
		}
		if (date) {
			this.setDate(date);
		}
		if (this.render) {
			this.render(); // TODO: deprecate
		}
		this.renderDates();
		this.updateSize();
		this.renderBusinessHours(); // might need coordinates, so should go after updateSize()
		this.startNowIndicator();
	},


	// Unrenders the view content that was rendered in displayView.
	// Can be asynchronous and return a promise.
	clearView: function() {
		this.unselect();
		this.stopNowIndicator();
		this.triggerUnrender();
		this.unrenderBusinessHours();
		this.unrenderDates();
		if (this.destroy) {
			this.destroy(); // TODO: deprecate
		}
	},


	// Renders the basic structure of the view before any content is rendered
	renderSkeleton: function() {
		// subclasses should implement
	},


	// Unrenders the basic structure of the view
	unrenderSkeleton: function() {
		// subclasses should implement
	},


	// Renders the view's date-related content.
	// Assumes setRange has already been called and the skeleton has already been rendered.
	renderDates: function() {
		// subclasses should implement
	},


	// Unrenders the view's date-related content
	unrenderDates: function() {
		// subclasses should override
	},


	// Signals that the view's content has been rendered
	triggerRender: function() {
		this.trigger('viewRender', this, this, this.el);
	},


	// Signals that the view's content is about to be unrendered
	triggerUnrender: function() {
		this.trigger('viewDestroy', this, this, this.el);
	},


	// Binds DOM handlers to elements that reside outside the view container, such as the document
	bindGlobalHandlers: function() {
		this.listenTo($(document), 'mousedown', this.handleDocumentMousedown);
		this.listenTo($(document), 'touchstart', this.processUnselect);
	},


	// Unbinds DOM handlers from elements that reside outside the view container
	unbindGlobalHandlers: function() {
		this.stopListeningTo($(document));
	},


	// Initializes internal variables related to theming
	initThemingProps: function() {
		var tm = this.opt('theme') ? 'ui' : 'fc';

		this.widgetHeaderClass = tm + '-widget-header';
		this.widgetContentClass = tm + '-widget-content';
		this.highlightStateClass = tm + '-state-highlight';
	},


	/* Business Hours
	------------------------------------------------------------------------------------------------------------------*/


	// Renders business-hours onto the view. Assumes updateSize has already been called.
	renderBusinessHours: function() {
		// subclasses should implement
	},


	// Unrenders previously-rendered business-hours
	unrenderBusinessHours: function() {
		// subclasses should implement
	},


	/* Now Indicator
	------------------------------------------------------------------------------------------------------------------*/


	// Immediately render the current time indicator and begins re-rendering it at an interval,
	// which is defined by this.getNowIndicatorUnit().
	// TODO: somehow do this for the current whole day's background too
	startNowIndicator: function() {
		var _this = this;
		var unit;
		var update;
		var delay; // ms wait value

		if (this.opt('nowIndicator')) {
			unit = this.getNowIndicatorUnit();
			if (unit) {
				update = proxy(this, 'updateNowIndicator'); // bind to `this`

				this.initialNowDate = this.calendar.getNow();
				this.initialNowQueriedMs = +new Date();
				this.renderNowIndicator(this.initialNowDate);
				this.isNowIndicatorRendered = true;

				// wait until the beginning of the next interval
				delay = this.initialNowDate.clone().startOf(unit).add(1, unit) - this.initialNowDate;
				this.nowIndicatorTimeoutID = setTimeout(function() {
					_this.nowIndicatorTimeoutID = null;
					update();
					delay = +moment.duration(1, unit);
					delay = Math.max(100, delay); // prevent too frequent
					_this.nowIndicatorIntervalID = setInterval(update, delay); // update every interval
				}, delay);
			}
		}
	},


	// rerenders the now indicator, computing the new current time from the amount of time that has passed
	// since the initial getNow call.
	updateNowIndicator: function() {
		if (this.isNowIndicatorRendered) {
			this.unrenderNowIndicator();
			this.renderNowIndicator(
				this.initialNowDate.clone().add(new Date() - this.initialNowQueriedMs) // add ms
			);
		}
	},


	// Immediately unrenders the view's current time indicator and stops any re-rendering timers.
	// Won't cause side effects if indicator isn't rendered.
	stopNowIndicator: function() {
		if (this.isNowIndicatorRendered) {

			if (this.nowIndicatorTimeoutID) {
				clearTimeout(this.nowIndicatorTimeoutID);
				this.nowIndicatorTimeoutID = null;
			}
			if (this.nowIndicatorIntervalID) {
				clearTimeout(this.nowIndicatorIntervalID);
				this.nowIndicatorIntervalID = null;
			}

			this.unrenderNowIndicator();
			this.isNowIndicatorRendered = false;
		}
	},


	// Returns a string unit, like 'second' or 'minute' that defined how often the current time indicator
	// should be refreshed. If something falsy is returned, no time indicator is rendered at all.
	getNowIndicatorUnit: function() {
		// subclasses should implement
	},


	// Renders a current time indicator at the given datetime
	renderNowIndicator: function(date) {
		// subclasses should implement
	},


	// Undoes the rendering actions from renderNowIndicator
	unrenderNowIndicator: function() {
		// subclasses should implement
	},


	/* Dimensions
	------------------------------------------------------------------------------------------------------------------*/


	// Refreshes anything dependant upon sizing of the container element of the grid
	updateSize: function(isResize) {
		var scrollState;

		if (isResize) {
			scrollState = this.queryScroll();
		}

		this.updateHeight(isResize);
		this.updateWidth(isResize);
		this.updateNowIndicator();

		if (isResize) {
			this.setScroll(scrollState);
		}
	},


	// Refreshes the horizontal dimensions of the calendar
	updateWidth: function(isResize) {
		// subclasses should implement
	},


	// Refreshes the vertical dimensions of the calendar
	updateHeight: function(isResize) {
		var calendar = this.calendar; // we poll the calendar for height information

		this.setHeight(
			calendar.getSuggestedViewHeight(),
			calendar.isHeightAuto()
		);
	},


	// Updates the vertical dimensions of the calendar to the specified height.
	// if `isAuto` is set to true, height becomes merely a suggestion and the view should use its "natural" height.
	setHeight: function(height, isAuto) {
		// subclasses should implement
	},


	/* Scroller
	------------------------------------------------------------------------------------------------------------------*/


	// Computes the initial pre-configured scroll state prior to allowing the user to change it.
	// Given the scroll state from the previous rendering. If first time rendering, given null.
	computeInitialScroll: function(previousScrollState) {
		return 0;
	},


	// Retrieves the view's current natural scroll state. Can return an arbitrary format.
	queryScroll: function() {
		// subclasses must implement
	},


	// Sets the view's scroll state. Will accept the same format computeInitialScroll and queryScroll produce.
	setScroll: function(scrollState) {
		// subclasses must implement
	},


	// Sets the scroll state, making sure to overcome any predefined scroll value the browser has in mind
	forceScroll: function(scrollState) {
		var _this = this;

		this.setScroll(scrollState);
		setTimeout(function() {
			_this.setScroll(scrollState);
		}, 0);
	},


	/* Event Elements / Segments
	------------------------------------------------------------------------------------------------------------------*/


	// Does everything necessary to display the given events onto the current view
	displayEvents: function(events) {
		var scrollState = this.queryScroll();

		this.clearEvents();
		this.renderEvents(events);
		this.isEventsRendered = true;
		this.setScroll(scrollState);
		this.triggerEventRender();
	},


	// Does everything necessary to clear the view's currently-rendered events
	clearEvents: function() {
		var scrollState;

		if (this.isEventsRendered) {

			// TODO: optimize: if we know this is part of a displayEvents call, don't queryScroll/setScroll
			scrollState = this.queryScroll();

			this.triggerEventUnrender();
			if (this.destroyEvents) {
				this.destroyEvents(); // TODO: deprecate
			}
			this.unrenderEvents();
			this.setScroll(scrollState);
			this.isEventsRendered = false;
		}
	},


	// Renders the events onto the view.
	renderEvents: function(events) {
		// subclasses should implement
	},


	// Removes event elements from the view.
	unrenderEvents: function() {
		// subclasses should implement
	},


	// Signals that all events have been rendered
	triggerEventRender: function() {
		this.renderedEventSegEach(function(seg) {
			this.trigger('eventAfterRender', seg.event, seg.event, seg.el);
		});
		this.trigger('eventAfterAllRender');
	},


	// Signals that all event elements are about to be removed
	triggerEventUnrender: function() {
		this.renderedEventSegEach(function(seg) {
			this.trigger('eventDestroy', seg.event, seg.event, seg.el);
		});
	},


	// Given an event and the default element used for rendering, returns the element that should actually be used.
	// Basically runs events and elements through the eventRender hook.
	resolveEventEl: function(event, el) {
		var custom = this.trigger('eventRender', event, event, el);

		if (custom === false) { // means don't render at all
			el = null;
		}
		else if (custom && custom !== true) {
			el = $(custom);
		}

		return el;
	},


	// Hides all rendered event segments linked to the given event
	showEvent: function(event) {
		this.renderedEventSegEach(function(seg) {
			seg.el.css('visibility', '');
		}, event);
	},


	// Shows all rendered event segments linked to the given event
	hideEvent: function(event) {
		this.renderedEventSegEach(function(seg) {
			seg.el.css('visibility', 'hidden');
		}, event);
	},


	// Iterates through event segments that have been rendered (have an el). Goes through all by default.
	// If the optional `event` argument is specified, only iterates through segments linked to that event.
	// The `this` value of the callback function will be the view.
	renderedEventSegEach: function(func, event) {
		var segs = this.getEventSegs();
		var i;

		for (i = 0; i < segs.length; i++) {
			if (!event || segs[i].event._id === event._id) {
				if (segs[i].el) {
					func.call(this, segs[i]);
				}
			}
		}
	},


	// Retrieves all the rendered segment objects for the view
	getEventSegs: function() {
		// subclasses must implement
		return [];
	},


	/* Event Drag-n-Drop
	------------------------------------------------------------------------------------------------------------------*/


	// Computes if the given event is allowed to be dragged by the user
	isEventDraggable: function(event) {
		var source = event.source || {};

		return firstDefined(
			event.startEditable,
			source.startEditable,
			this.opt('eventStartEditable'),
			event.editable,
			source.editable,
			this.opt('editable')
		);
	},


	// Must be called when an event in the view is dropped onto new location.
	// `dropLocation` is an object that contains the new zoned start/end/allDay values for the event.
	reportEventDrop: function(event, dropLocation, largeUnit, el, ev) {
		var calendar = this.calendar;
		var mutateResult = calendar.mutateEvent(event, dropLocation, largeUnit);
		var undoFunc = function() {
			mutateResult.undo();
			calendar.reportEventChange();
		};

		this.triggerEventDrop(event, mutateResult.dateDelta, undoFunc, el, ev);
		calendar.reportEventChange(); // will rerender events
	},


	// Triggers event-drop handlers that have subscribed via the API
	triggerEventDrop: function(event, dateDelta, undoFunc, el, ev) {
		this.trigger('eventDrop', el[0], event, dateDelta, undoFunc, ev, {}); // {} = jqui dummy
	},


	/* External Element Drag-n-Drop
	------------------------------------------------------------------------------------------------------------------*/


	// Must be called when an external element, via jQuery UI, has been dropped onto the calendar.
	// `meta` is the parsed data that has been embedded into the dragging event.
	// `dropLocation` is an object that contains the new zoned start/end/allDay values for the event.
	reportExternalDrop: function(meta, dropLocation, el, ev, ui) {
		var eventProps = meta.eventProps;
		var eventInput;
		var event;

		// Try to build an event object and render it. TODO: decouple the two
		if (eventProps) {
			eventInput = $.extend({}, eventProps, dropLocation);
			event = this.calendar.renderEvent(eventInput, meta.stick)[0]; // renderEvent returns an array
		}

		this.triggerExternalDrop(event, dropLocation, el, ev, ui);
	},


	// Triggers external-drop handlers that have subscribed via the API
	triggerExternalDrop: function(event, dropLocation, el, ev, ui) {

		// trigger 'drop' regardless of whether element represents an event
		this.trigger('drop', el[0], dropLocation.start, ev, ui);

		if (event) {
			this.trigger('eventReceive', null, event); // signal an external event landed
		}
	},


	/* Drag-n-Drop Rendering (for both events and external elements)
	------------------------------------------------------------------------------------------------------------------*/


	// Renders a visual indication of a event or external-element drag over the given drop zone.
	// If an external-element, seg will be `null`.
	// Must return elements used for any mock events.
	renderDrag: function(dropLocation, seg) {
		// subclasses must implement
	},


	// Unrenders a visual indication of an event or external-element being dragged.
	unrenderDrag: function() {
		// subclasses must implement
	},


	/* Event Resizing
	------------------------------------------------------------------------------------------------------------------*/


	// Computes if the given event is allowed to be resized from its starting edge
	isEventResizableFromStart: function(event) {
		return this.opt('eventResizableFromStart') && this.isEventResizable(event);
	},


	// Computes if the given event is allowed to be resized from its ending edge
	isEventResizableFromEnd: function(event) {
		return this.isEventResizable(event);
	},


	// Computes if the given event is allowed to be resized by the user at all
	isEventResizable: function(event) {
		var source = event.source || {};

		return firstDefined(
			event.durationEditable,
			source.durationEditable,
			this.opt('eventDurationEditable'),
			event.editable,
			source.editable,
			this.opt('editable')
		);
	},


	// Must be called when an event in the view has been resized to a new length
	reportEventResize: function(event, resizeLocation, largeUnit, el, ev) {
		var calendar = this.calendar;
		var mutateResult = calendar.mutateEvent(event, resizeLocation, largeUnit);
		var undoFunc = function() {
			mutateResult.undo();
			calendar.reportEventChange();
		};

		this.triggerEventResize(event, mutateResult.durationDelta, undoFunc, el, ev);
		calendar.reportEventChange(); // will rerender events
	},


	// Triggers event-resize handlers that have subscribed via the API
	triggerEventResize: function(event, durationDelta, undoFunc, el, ev) {
		this.trigger('eventResize', el[0], event, durationDelta, undoFunc, ev, {}); // {} = jqui dummy
	},


	/* Selection (time range)
	------------------------------------------------------------------------------------------------------------------*/


	// Selects a date span on the view. `start` and `end` are both Moments.
	// `ev` is the native mouse event that begin the interaction.
	select: function(span, ev) {
		this.unselect(ev);
		this.renderSelection(span);
		this.reportSelection(span, ev);
	},


	// Renders a visual indication of the selection
	renderSelection: function(span) {
		// subclasses should implement
	},


	// Called when a new selection is made. Updates internal state and triggers handlers.
	reportSelection: function(span, ev) {
		this.isSelected = true;
		this.triggerSelect(span, ev);
	},


	// Triggers handlers to 'select'
	triggerSelect: function(span, ev) {
		this.trigger(
			'select',
			null,
			this.calendar.applyTimezone(span.start), // convert to calendar's tz for external API
			this.calendar.applyTimezone(span.end), // "
			ev
		);
	},


	// Undoes a selection. updates in the internal state and triggers handlers.
	// `ev` is the native mouse event that began the interaction.
	unselect: function(ev) {
		if (this.isSelected) {
			this.isSelected = false;
			if (this.destroySelection) {
				this.destroySelection(); // TODO: deprecate
			}
			this.unrenderSelection();
			this.trigger('unselect', null, ev);
		}
	},


	// Unrenders a visual indication of selection
	unrenderSelection: function() {
		// subclasses should implement
	},


	/* Event Selection
	------------------------------------------------------------------------------------------------------------------*/


	selectEvent: function(event) {
		if (!this.selectedEvent || this.selectedEvent !== event) {
			this.unselectEvent();
			this.renderedEventSegEach(function(seg) {
				seg.el.addClass('fc-selected');
			}, event);
			this.selectedEvent = event;
		}
	},


	unselectEvent: function() {
		if (this.selectedEvent) {
			this.renderedEventSegEach(function(seg) {
				seg.el.removeClass('fc-selected');
			}, this.selectedEvent);
			this.selectedEvent = null;
		}
	},


	isEventSelected: function(event) {
		// event references might change on refetchEvents(), while selectedEvent doesn't,
		// so compare IDs
		return this.selectedEvent && this.selectedEvent._id === event._id;
	},


	/* Mouse / Touch Unselecting (time range & event unselection)
	------------------------------------------------------------------------------------------------------------------*/
	// TODO: move consistently to down/start or up/end?
	// TODO: don't kill previous selection if touch scrolling


	handleDocumentMousedown: function(ev) {
		if (isPrimaryMouseButton(ev)) {
			this.processUnselect(ev);
		}
	},


	processUnselect: function(ev) {
		this.processRangeUnselect(ev);
		this.processEventUnselect(ev);
	},


	processRangeUnselect: function(ev) {
		var ignore;

		// is there a time-range selection?
		if (this.isSelected && this.opt('unselectAuto')) {
			// only unselect if the clicked element is not identical to or inside of an 'unselectCancel' element
			ignore = this.opt('unselectCancel');
			if (!ignore || !$(ev.target).closest(ignore).length) {
				this.unselect(ev);
			}
		}
	},


	processEventUnselect: function(ev) {
		if (this.selectedEvent) {
			if (!$(ev.target).closest('.fc-selected').length) {
				this.unselectEvent();
			}
		}
	},


	/* Day Click
	------------------------------------------------------------------------------------------------------------------*/


	// Triggers handlers to 'dayClick'
	// Span has start/end of the clicked area. Only the start is useful.
	triggerDayClick: function(span, dayEl, ev) {
		this.trigger(
			'dayClick',
			dayEl,
			this.calendar.applyTimezone(span.start), // convert to calendar's timezone for external API
			ev
		);
	},


	/* Date Utils
	------------------------------------------------------------------------------------------------------------------*/


	// Initializes internal variables related to calculating hidden days-of-week
	initHiddenDays: function() {
		var hiddenDays = this.opt('hiddenDays') || []; // array of day-of-week indices that are hidden
		var isHiddenDayHash = []; // is the day-of-week hidden? (hash with day-of-week-index -> bool)
		var dayCnt = 0;
		var i;

		if (this.opt('weekends') === false) {
			hiddenDays.push(0, 6); // 0=sunday, 6=saturday
		}

		for (i = 0; i < 7; i++) {
			if (
				!(isHiddenDayHash[i] = $.inArray(i, hiddenDays) !== -1)
			) {
				dayCnt++;
			}
		}

		if (!dayCnt) {
			throw 'invalid hiddenDays'; // all days were hidden? bad.
		}

		this.isHiddenDayHash = isHiddenDayHash;
	},


	// Is the current day hidden?
	// `day` is a day-of-week index (0-6), or a Moment
	isHiddenDay: function(day) {
		if (moment.isMoment(day)) {
			day = day.day();
		}
		return this.isHiddenDayHash[day];
	},


	// Incrementing the current day until it is no longer a hidden day, returning a copy.
	// If the initial value of `date` is not a hidden day, don't do anything.
	// Pass `isExclusive` as `true` if you are dealing with an end date.
	// `inc` defaults to `1` (increment one day forward each time)
	skipHiddenDays: function(date, inc, isExclusive) {
		var out = date.clone();
		inc = inc || 1;
		while (
			this.isHiddenDayHash[(out.day() + (isExclusive ? inc : 0) + 7) % 7]
		) {
			out.add(inc, 'days');
		}
		return out;
	},


	// Returns the date range of the full days the given range visually appears to occupy.
	// Returns a new range object.
	computeDayRange: function(range) {
		var startDay = range.start.clone().stripTime(); // the beginning of the day the range starts
		var end = range.end;
		var endDay = null;
		var endTimeMS;

		if (end) {
			endDay = end.clone().stripTime(); // the beginning of the day the range exclusively ends
			endTimeMS = +end.time(); // # of milliseconds into `endDay`

			// If the end time is actually inclusively part of the next day and is equal to or
			// beyond the next day threshold, adjust the end to be the exclusive end of `endDay`.
			// Otherwise, leaving it as inclusive will cause it to exclude `endDay`.
			if (endTimeMS && endTimeMS >= this.nextDayThreshold) {
				endDay.add(1, 'days');
			}
		}

		// If no end was specified, or if it is within `startDay` but not past nextDayThreshold,
		// assign the default duration of one day.
		if (!end || endDay <= startDay) {
			endDay = startDay.clone().add(1, 'days');
		}

		return { start: startDay, end: endDay };
	},


	// Does the given event visually appear to occupy more than one day?
	isMultiDayEvent: function(event) {
		var range = this.computeDayRange(event); // event is range-ish

		return range.end.diff(range.start, 'days') > 1;
	}

});
