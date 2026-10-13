BX.namespace('Belveb.Booking.Domain');

(function (Domain) {
    Domain.Person = function (id, name) {
        if (id < 1 || !name) {
            throw new Error('Person id and name are required');
        }
        this.personId = id;
        this.personName = name;
    };

    Domain.Person.prototype.id = function () {
        return this.personId;
    };

    Domain.Person.prototype.name = function () {
        return this.personName;
    };

    Domain.Person.prototype.known = function () {
        return this.personId > 0;
    };

    Domain.Person.prototype.same = function (other) {
        return this.known() && other.known() && this.personId === other.id();
    };

    Domain.Person.prototype.view = function () {
        return {id: this.personId, name: this.personName};
    };

    Domain.Nobody = function () {
        this.personId = 0;
        this.personName = '';
    };

    Domain.Nobody.prototype = Object.create(Domain.Person.prototype);
    Domain.Nobody.prototype.known = function () {
        return false;
    };
    Domain.Nobody.prototype.same = function () {
        return false;
    };
    Domain.Nobody.prototype.id = function () {
        return 0;
    };
    Domain.Nobody.prototype.name = function () {
        return '';
    };
    Domain.Nobody.prototype.view = function () {
        return {id: 0, name: ''};
    };

    Domain.ParticipantList = function (people) {
        this.people = people.slice();
    };

    Domain.ParticipantList.prototype.count = function () {
        return this.people.length;
    };

    Domain.ParticipantList.prototype.countLabel = function () {
        return this.count() + ' человек';
    };

    Domain.ParticipantList.prototype.has = function (person) {
        return this.people.some(function (current) {
            return current.same(person);
        });
    };

    Domain.ParticipantList.prototype.with = function (person) {
        if (person instanceof Domain.Nobody || this.has(person)) {
            return this;
        }
        return new Domain.ParticipantList(this.people.concat([person]));
    };

    Domain.ParticipantList.prototype.without = function (person) {
        return new Domain.ParticipantList(this.people.filter(function (current) {
            return !current.same(person);
        }));
    };

    Domain.ParticipantList.prototype.fitsIn = function (room) {
        return this.count() <= room.capacity();
    };

    Domain.ParticipantList.prototype.ratio = function (room) {
        return this.count() + '/' + room.capacity();
    };

    Domain.ParticipantList.prototype.same = function (other) {
        var self = this;
        return this.count() === other.count() && this.people.every(function (person) {
            return other.has(person);
        }) && other.people.every(function (person) {
            return self.has(person);
        });
    };

    Domain.ParticipantList.prototype.view = function () {
        return this.people.map(function (person) {
            return person.view();
        });
    };

    Domain.SeriesRule = function (rule, from, horizonDays) {
        var known = ['none', 'daily', 'weekly', 'monthly'];
        if (known.indexOf(rule) === -1) {
            throw new Error('Unknown series rule');
        }
        this.ruleName = rule;
        this.ruleFrom = from;
        this.horizon = horizonDays;
    };

    Domain.SeriesRule.prototype.rule = function () {
        return this.ruleName;
    };

    Domain.SeriesRule.prototype.label = function () {
        if (this.ruleName === 'daily') {
            return 'Ежедневно, от ' + this.ruleFrom.text();
        }
        if (this.ruleName === 'weekly') {
            return 'Еженедельно, от ' + this.ruleFrom.text();
        }
        if (this.ruleName === 'monthly') {
            return 'Ежемесячно, от ' + this.ruleFrom.text();
        }
        return 'Без повтора';
    };

    Domain.SeriesRule.prototype.same = function (other) {
        return this.ruleName === other.rule() && this.ruleFrom.compare(other.ruleFrom) === 0;
    };

    Domain.SeriesRule.prototype.view = function () {
        return {rule: this.ruleName, from: this.ruleFrom.text(), label: this.label()};
    };
})(BX.Belveb.Booking.Domain);
