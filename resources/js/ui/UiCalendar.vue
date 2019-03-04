<template lang="html">
    <div>
        <div class="b-calendar">
            <div class="row">
                <div class="col">

                </div>
            </div>
        </div>
        <b-container>
            <main class="b-calendar">
                <b-row>
                    <b-col md="8">
                        <div class="b-calendar__calendar">
                            <div class="b-calendar__header">
                                <b-row>
                                    <b-col class="year text-right" align-h="end">
                                        <span>{{year}}</span>
                                    </b-col>
                                </b-row>
                                <b-row align-v="center">
                                    <b-col class="text-left" align-h="start">
                                        <b-button id="subtractMonthBtn" class="arrow arrow-left" variant="light" @click="subtractMonth">
                                            <i class="fa fa-fw fa-chevron-left"></i>
                                        </b-button>
                                        <b-tooltip target="subtractMonthBtn">
                                            {{previousMonthAsString | capitalize}}
                                        </b-tooltip>
                                    </b-col>
                                    <b-col class="text-center" align-h="center">
                                        <span class="month">{{month}}</span>
                                    </b-col>
                                    <b-col class="text-right d-flex flex-row-reverse" align-h="end">
                                        <b-button id="addMonthBtn" class="arrow arrow-right" variant="light" @click="addMonth">
                                            <i class="fa fa-fw fa-chevron-right"></i>
                                        </b-button>
                                        <b-tooltip target="addMonthBtn">
                                            {{nextMonthAsString | capitalize}}
                                        </b-tooltip>
                                    </b-col>
                                </b-row>
                            </div>
                            <div class="b-calendar__weekdays">
                                <div class="weekday" v-for="(day, index) in days" :key="index">
                                    <strong>{{day}}</strong>
                                </div>
                            </div>
                            <div class="b-calendar__dates">
                                <div class="date text-right" :class="{
                                'today': date.today,
                                'blank': date.blank,
                                'no-border-right': date.key % 7 === 0,
                             }"
                                    v-for="date in dateList" :key="date.key" :data-date="date.date" @click="setSelectedDate(date.moment)">
                                    <span class="day">{{date.dayNumber}}</span>
                                    <span class="weekday">{{date.weekDay}}</span>
                                    <div class="additional" v-show="date.additional">
                                        <span class="year" v-show="date.additional.year">{{date.additional.year}}</span>
                                        <span class="month" v-show="date.additional.month">{{date.additional.month}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-col>
                </b-row>
            </main>
        </b-container>
    </div>
</template>

<script>
import moment from 'moment'

export default {
    name: 'UiCalendar',
    data: function() {
        return {
            today: moment(),
            dateContext: moment(),
            selectedDate: moment(),
            days: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
        }
    },
    computed: {
        year: function() {
            return this.dateContext.format("Y");
        },

        month: function() {
            return this.dateContext.format("MMMM");
        },

        daysInMonth: function() {
            return this.dateContext.daysInMonth();
        },

        currentDate: function() {
            return this.dateContext.get("date");
        },

        firstDayOfMonth: function() {
            let firstDay = moment(this.dateContext).subtract(this.currentDate, "days");
            return firstDay.weekday();
        },

        previousMonth: function() {
            return moment(this.dateContext).subtract(1, "month");
        },
        previousMonthAsString: function() {
            return this.previousMonth.format("MMMM");
        },
        nextMonth: function() {
            return moment(this.dateContext).add(1, "month");
        },
        nextMonthAsString: function() {
            return this.nextMonth.format("MMMM");
        },

        daysInPreviousMonth: function() {
            return this.previousMonth.daysInMonth();
        },
        daysFromPreviousMonth: function() {
            let daysList = [];
            let count = this.daysInPreviousMonth - this.firstDayOfMonth;
            while (count < this.daysInPreviousMonth) {
                count++;
                daysList[count] = count;
            }

            return daysList.filter(function() {
                return true;
            });
        },
        dateList: function() {
            let $this = this;

            let dateList = [];

            let previousMonth = this.previousMonth;
            let nextMonth = this.nextMonth;

            //dates for display
            let formattedCurrentMonth = this.dateContext.format("MM");
            let formattedCurrentYear = this.year;
            let formattedPreviousMonth = previousMonth.format("MM");
            let formattedPreviousYear = previousMonth.format("Y");
            let formattedNextMonth = nextMonth.format("MM");
            let formattedNextYear = nextMonth.format("Y");

            //counters
            let countDayInCurrentMonth = 0;
            let countDayInPreviousMonth = 0;

            //filling in dates from the previous month
            this.daysFromPreviousMonth.forEach(function(dayFromPreviousMonth) {
                countDayInCurrentMonth++;
                countDayInPreviousMonth++;

                let formattedDay = $this.formattingDay(dayFromPreviousMonth);
                let previousMonth =
                    $this.daysFromPreviousMonth.length ===
                    countDayInPreviousMonth ?
                    $this.previousMonthAsString :
                    false;
                let previousYear =
                    formattedCurrentYear !== formattedPreviousYear &&
                    $this.daysFromPreviousMonth.length ===
                    countDayInPreviousMonth ?
                    formattedPreviousYear :
                    false;
                let additional = {
                    month: previousMonth,
                    year: previousYear
                };

                if (!previousMonth && !previousYear) {
                    additional = false;
                }

                dateList[countDayInCurrentMonth] = {
                    key: countDayInCurrentMonth,
                    dayNumber: formattedDay,
                    date: formattedDay +
                        "." +
                        formattedPreviousMonth +
                        "." +
                        formattedPreviousYear,
                    blank: true,
                    today: false,
                    additional: additional,
                    weekDay: false,
                    moment: moment(
                        formattedPreviousYear +
                        formattedPreviousMonth +
                        formattedDay
                    )
                };
            });

            //filling in dates from the current month
            while (countDayInCurrentMonth < this.firstDayOfMonth + this.daysInMonth) {
                countDayInCurrentMonth++;

                let day = countDayInCurrentMonth - countDayInPreviousMonth;
                let weekDay = this.getWeekDay(countDayInCurrentMonth);
                let formattedDay = this.formattingDay(day);

                dateList[countDayInCurrentMonth] = {
                    key: countDayInCurrentMonth,
                    dayNumber: formattedDay,
                    date: formattedDay +
                        "." +
                        formattedCurrentMonth +
                        "." +
                        formattedCurrentYear,
                    blank: false,
                    today: formattedDay === this.initialDate &&
                        this.todayInCurrentMonthAndYear,
                    additional: false,
                    weekDay: weekDay,
                    moment: moment(
                        formattedCurrentYear +
                        formattedCurrentMonth +
                        formattedDay
                    )
                };
            }

            let daysInNextMonth = 7 - (countDayInCurrentMonth % 7);
            let countDayInCurrentMonthSaved = countDayInCurrentMonth;
            let day = 0;

            //filling in dates from the next month
            if (daysInNextMonth < 7) {
                while (
                    countDayInCurrentMonth <
                    countDayInCurrentMonthSaved + daysInNextMonth
                ) {
                    countDayInCurrentMonth++;
                    day++;

                    let formattedDay = this.formattingDay(day);
                    let nextMonth = day === 1 ? this.nextMonthAsString : false;
                    let nextYear =
                        formattedCurrentYear !== formattedNextYear && day === 1 ?
                        formattedNextYear :
                        false;
                    let additional = {
                        month: nextMonth,
                        year: nextYear
                    };

                    if (!nextMonth && !nextYear) {
                        additional = false;
                    }

                    dateList[countDayInCurrentMonth] = {
                        key: countDayInCurrentMonth,
                        dayNumber: formattedDay,
                        date: formattedDay +
                            "." +
                            formattedNextMonth +
                            "." +
                            formattedNextYear,
                        blank: true,
                        today: false,
                        additional: additional,
                        weekDay: false,
                        moment: moment(
                            formattedNextYear +
                            formattedNextMonth +
                            formattedDay
                        )
                    };
                }
            }

            return dateList.filter(function() {
                return true;
            });
        },

        initialDate: function() {
            return this.formattingDay(this.today.get("date"));
        },
        initialMonth: function() {
            return this.today.format("MMMM");
        },
        initialYear: function() {
            return this.today.format("Y");
        },
        todayInCurrentMonthAndYear: function() {
            return (
                this.month === this.initialMonth &&
                this.year === this.initialYear
            );
        },
        selectedDayAndMonth: function() {
            let dayAndMonth = this.selectedDate.format("D MMMM");
            dayAndMonth = dayAndMonth.split(" ");
            dayAndMonth = {
                day: dayAndMonth[0],
                month: dayAndMonth[1]
            };

            return dayAndMonth;
        },
        selectedWeekDay: function() {
            return this.selectedDate.format("dddd");
        },
        todayIsEqualSelectDate: function() {
            return (
                this.selectedDate.format("YYYYMMDD") ===
                this.today.format("YYYYMMDD")
            );
        }
    },
    methods: {
        addMonth: function() {
            this.dateContext = this.nextMonth;
        },
        subtractMonth: function() {
            this.dateContext = this.previousMonth;
        },
        setSelectedDate: function(moment) {
            this.selectedDate = moment;
        },
        goToday: function() {
            this.selectedDate = this.today;
            this.dateContext = this.today;
        },
        formattingDay(day) {
            return ("0" + day).slice(-2);
        },
        getWeekDay(day) {
            let index = day;
            if (index > 7) {
                index %= 7;
            }
            index = index === 0 ? 6 : index - 1;
            return this.days[index];
        }
    },
    filters: {
        capitalize: function(value) {
            if (!value) return "";
            value = value.toString();
            return value.charAt(0).toUpperCase() + value.slice(1);
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
.b-calendar {
    display: flex;
    align-items: center;
    margin: 2.5em auto;
    max-width: 70rem;
    &__information {
        background-color: #535c68;
        border-radius: 1.2rem 0 0 1.2rem;
        color: #fff;
        height: 100%;
        padding-left: 0.9rem;
        padding-right: 0.9rem;
        .selected-date {
            padding-top: 3rem;
            padding-left: 2.5rem;
            position: relative;
            &:before {
                content: "";
                position: absolute;
                background-color: #f0932b;
                border-radius: 50%;
                width: 2rem;
                height: 2rem;
                top: 4.5rem;
                left: -0.25rem;
            }
            .weekday {
                font-weight: 100;
                padding-bottom: 0.5em;
            }
            .day {
                font-size: 2em;
                font-weight: 600;
                line-height: 1;
            }
            .month {
                font-size: 2em;
                font-weight: 200;
                line-height: 1;
            }
        }
        .go-today-link {
            display: none;
        }
    }
    &__calendar {
        min-height: 40rem;
        padding-right: 0.9rem;
    }
    &__header {
        margin-bottom: 2rem;
        .month {
            font-size: 1.25em;
            font-weight: 200;
            text-transform: capitalize;
            min-width: 10rem;
        }
        .year {
            font-size: 1.5em;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .arrow {
            background: transparent;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 2.5rem;
            width: 2.5rem;
            &:hover {
                cursor: pointer;
            }
            &-left {
                i {
                    transform: translateX(-10%);
                }
            }
            &-right {
                i {
                    transform: translateX(10%);
                }
            }
        }
    }
    &__weekdays {
        display: flex;
        margin-bottom: 1.25rem;
        .weekday {
            width: calc(100% / 7);
            padding: 0.25rem 0.5rem;
        }
    }
    &__dates {
        display: flex;
        flex-wrap: wrap;
        position: relative;
        &:after {
            content: "";
            position: absolute;
            bottom: 0;
            background-color: #fff;
            height: 1px;
            width: 100%;
            z-index: 1;
        }
        .date {
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 200;
            min-height: 4.5rem;
            padding: 0.25rem 0.5rem;
            position: relative;
            width: calc(100% / 7);
            &.blank {
                background-color: rgba(0, 0, 0, 0.02);
                color: rgba(0, 0, 0, 0.2);
            }
            &.no-border-right {
                border-right: none;
            }
            &.today {
                background-color: #ff7979;
                color: #fff;
            }
            &.selected {
                background-color: #f0932b;
                color: #fff;
            }
            .link {
                cursor: pointer;
                position: absolute;
                top: 0.05rem;
                left: 0.05rem;
                bottom: 0.05rem;
                right: 0.05rem;
                z-index: 1;
            }
            .weekday {
                display: none;
            }
            .additional {
                font-size: 0.75em;
                position: absolute;
                bottom: 0.25rem;
                left: 0.5rem;
                .year {
                    padding-right: 0.25rem;
                    font-size: 0.75em;
                }
            }
            .event-mobile-container {
                display: none;
            }
        }
    }
}

.b-event-container {
    margin-top: 2rem;
    .create-event-form {
        margin-bottom: 1rem;
        .event-title-input {
            margin-right: 0.25rem;
            width: calc(100% - 2.7rem);
        }
    }
    .btn-group {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
        .btn {
            width: 33.33%;
        }
    }
    .event-list {
        list-style: none;
        padding: 0;
        margin-left: -0.9rem;
        margin-right: -0.9rem;
        max-height: 20rem;
        overflow-y: auto;
        .event {
            background-color: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0.9rem;
            margin-bottom: 0.5rem;
            &.completed {
                background-color: rgba(255, 255, 255, 0.2);
                .title {
                    opacity: 0.5;
                    &:before {
                        content: "";
                        position: absolute;
                        top: 50%;
                        left: -0.5rem;
                        display: block;
                        width: 0%;
                        height: 1px;
                        background: #fff;
                        animation: strikeitem 0.3s ease-out 0s forwards;
                    }
                }
            }
            .title {
                position: relative;
                max-width: calc(100% - 4rem);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .buttons {
                display: flex;
                .btn {
                    background: transparent;
                    border: none;
                    color: #fff;
                    font-size: 1.2em;
                    padding: 0;
                    height: 1.75rem;
                    width: 1.75rem;
                    box-shadow: none;
                }
            }
        }
    }
    .no-events {
        margin-top: 1.5rem;
    }
}

@keyframes strikeitem {
    to {
        width: calc(100% + 1rem);
    }
}

@media (max-width: 991.98px) {
    .b-calendar {
        &__calendar {
            padding: 0;
        }
        &__information {
            border-radius: 2rem 2rem 0 0;
            padding-top: 2rem;
            padding-bottom: 2rem;
            min-height: auto;
            .today {
                padding-top: 0;
            }
        }
    }
}

@media (max-width: 480px) {
    .b-calendar {
        &__weekdays {
            display: none;
        }
        &__information {
            .selected-date {
                padding-top: 2rem;
                padding-bottom: 1rem;
                padding-left: 2.5rem;
                &:before {
                    top: 3rem;
                }
            }
            .b-event-container {
                display: none;
            }
            .go-today-link {
                display: block;
                color: #fff;
            }
        }
        &__header {
            .go-today-btn {
                display: none;
            }
            .month-change-container {
                width: 100%;
                min-width: 100%;
            }
            .month {
                width: 100%;
            }
        }
        &__dates {
            .date {
                width: 100%;
                text-align: left !important;
                border: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                display: flex;
                &.blank {
                    display: none;
                }
                .weekday {
                    display: block;
                    margin-left: 0.25rem;
                }
                .event-mobile-container {
                    background: #535c68;
                    display: block;
                    position: absolute;
                    margin-top: 0;
                    top: 100%;
                    left: 0;
                    right: 0;
                    padding: 2rem 0.5rem;
                    z-index: 10;
                    &.collapse {
                        display: none !important;
                    }
                    &.show {
                        display: block !important;
                    }
                }
            }
        }
    }
}

</style>
