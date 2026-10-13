BX.namespace('Belveb.Booking.Domain');

(function (Domain) {
    Domain.MinuteOfDay = function (hhmm) {
        if (!/^\d{2}:\d{2}$/.test(hhmm)) {
            throw new Error('MinuteOfDay expects HH:MM');
        }
        var parts = hhmm.split(':');
        var hour = Number(parts[0]);
        var minute = Number(parts[1]);
        if (hour > 23 || minute > 59) {
            throw new Error('MinuteOfDay is out of range');
        }
        this.minutes = hour * 60 + minute;
    };

    Domain.MinuteOfDay.prototype.text = function () {
        var hour = Math.floor(this.minutes / 60);
        var minute = this.minutes % 60;
        return String(hour).padStart(2, '0') + ':' + String(minute).padStart(2, '0');
    };

    Domain.MinuteOfDay.prototype.total = function () {
        return this.minutes;
    };

    Domain.MinuteOfDay.prototype.compare = function (other) {
        return this.minutes - other.minutes;
    };

    Domain.MinuteOfDay.prototype.floorTo = function (step) {
        return new Domain.MinuteOfDay(formatMinutes(Math.floor(this.minutes / step) * step));
    };

    Domain.MinuteOfDay.prototype.ceilTo = function (step) {
        var ceiled = Math.ceil(this.minutes / step) * step;
        if (ceiled >= 24 * 60) {
            ceiled = 24 * 60 - step;
        }
        return new Domain.MinuteOfDay(formatMinutes(ceiled));
    };

    Domain.MinuteOfDay.prototype.plus = function (delta) {
        var next = this.minutes + delta;
        if (next < 0 || next >= 24 * 60) {
            throw new Error('MinuteOfDay shift leaves the day');
        }
        return new Domain.MinuteOfDay(formatMinutes(next));
    };

    Domain.CalendarDate = function (value) {
        var match = /^(\d{2})\.(\d{2})\.(\d{4})$/.exec(value) || /^(\d{4})-(\d{2})-(\d{2})$/.exec(value);
        if (!match) {
            throw new Error('CalendarDate expects d.m.Y');
        }
        if (match[1].length === 4) {
            this.isoValue = match[1] + '-' + match[2] + '-' + match[3];
            this.textValue = match[3] + '.' + match[2] + '.' + match[1];
        } else {
            this.textValue = match[1] + '.' + match[2] + '.' + match[3];
            this.isoValue = match[3] + '-' + match[2] + '-' + match[1];
        }
        this.stamp = Date.parse(this.isoValue + 'T00:00:00');
        if (Number.isNaN(this.stamp)) {
            throw new Error('CalendarDate expects d.m.Y');
        }
    };

    Domain.CalendarDate.prototype.text = function () {
        return this.textValue;
    };

    Domain.CalendarDate.prototype.compare = function (other) {
        return this.isoValue < other.isoValue ? -1 : (this.isoValue > other.isoValue ? 1 : 0);
    };

    Domain.CalendarDate.prototype.plusDays = function (days) {
        var date = new Date(this.stamp);
        date.setDate(date.getDate() + days);
        var dd = String(date.getDate()).padStart(2, '0');
        var mm = String(date.getMonth() + 1).padStart(2, '0');
        return new Domain.CalendarDate(dd + '.' + mm + '.' + date.getFullYear());
    };

    Domain.CalendarDate.prototype.weekday = function () {
        var day = new Date(this.stamp).getDay();
        return day === 0 ? 7 : day;
    };

    Domain.TimeRange = function (start, end) {
        if (end.total() <= start.total()) {
            throw new Error('TimeRange end must be after start');
        }
        this.rangeStart = start;
        this.rangeEnd = end;
    };

    Domain.TimeRange.prototype.start = function () {
        return this.rangeStart;
    };

    Domain.TimeRange.prototype.end = function () {
        return this.rangeEnd;
    };

    Domain.TimeRange.prototype.overlaps = function (other) {
        return this.rangeStart.total() < other.end().total() && this.rangeEnd.total() > other.start().total();
    };

    Domain.TimeRange.prototype.durationMinutes = function () {
        return this.rangeEnd.total() - this.rangeStart.total();
    };

    Domain.TimeRange.prototype.withDuration = function (minutes) {
        return new Domain.TimeRange(this.rangeStart, this.rangeStart.plus(minutes));
    };

    Domain.TimeRange.prototype.label = function () {
        return this.rangeStart.text() + ' – ' + this.rangeEnd.text();
    };

    Domain.TimeRange.prototype.covers = function (moment) {
        return moment.total() >= this.rangeStart.total() && moment.total() < this.rangeEnd.total();
    };

    Domain.TimeRange.prototype.same = function (other) {
        return this.rangeStart.total() === other.start().total() && this.rangeEnd.total() === other.end().total();
    };

    Domain.Grid = function (from, to, stepMinutes) {
        if (stepMinutes < 1 || to.total() <= from.total()) {
            throw new Error('Grid is invalid');
        }
        this.gridFrom = from;
        this.gridTo = to;
        this.step = stepMinutes;
    };

    Domain.Grid.prototype.stepMinutes = function () {
        return this.step;
    };

    Domain.Grid.prototype.from = function () {
        return this.gridFrom;
    };

    Domain.Grid.prototype.to = function () {
        return this.gridTo;
    };

    Domain.Grid.prototype.contains = function (range) {
        return range.start().total() >= this.gridFrom.total() && range.end().total() <= this.gridTo.total();
    };

    Domain.Grid.prototype.align = function (range) {
        var start = range.start().floorTo(this.step);
        var end = range.end().ceilTo(this.step);
        if (end.total() <= start.total()) {
            end = start.plus(this.step);
        }
        return new Domain.TimeRange(start, end);
    };

    Domain.Grid.prototype.cells = function () {
        var cells = [];
        var cursor = this.gridFrom;
        while (cursor.total() < this.gridTo.total()) {
            var next = cursor.plus(this.step);
            if (next.total() > this.gridTo.total()) {
                break;
            }
            cells.push(new Domain.TimeRange(cursor, next));
            cursor = next;
        }
        return cells;
    };

    Domain.Grid.prototype.view = function () {
        return {
            workFrom: this.gridFrom.text(),
            workTo: this.gridTo.text(),
            stepMinutes: this.step,
        };
    };

    Domain.FixedClock = function (date, time) {
        this.clockDate = date;
        this.clockTime = time;
    };

    Domain.FixedClock.prototype.date = function () {
        return this.clockDate;
    };

    Domain.FixedClock.prototype.time = function () {
        return this.clockTime;
    };

    function formatMinutes(total) {
        var hour = Math.floor(total / 60);
        var minute = total % 60;
        return String(hour).padStart(2, '0') + ':' + String(minute).padStart(2, '0');
    }
})(BX.Belveb.Booking.Domain);
