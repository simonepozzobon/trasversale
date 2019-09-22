<template>
<div>
    <div class="b-calendar">
        <div class="b-calendar__header">
            <span>{{ month }} {{ year }}</span>
        </div>
        <div class="b-calendar__weekdays">
            <div
                class="weekday"
                v-for="(day, index) in days"
                :key="index"
            >
                <span>{{day}}</span>
            </div>
        </div>
        <div class="b-calendar__calendar">
            <div class="b-calendar__dates">
                <div
                    class="date text-right"
                    v-for="date in dateList"
                    :key="date.key"
                    :data-date="date.date"
                    :class="{
                                'selected': dateIsEqualSelectDate(date),
                            }"
                    @click="setSelectedDate(date.moment)"
                >
                    <div
                        class="date__information"
                        :class="{
                                    'today': date.today,
                                    'blank': date.blank,
                                    'no-border-right': date.key % 7 === 0
                                }"
                    >
                        <span class="day">{{date.dayNumber}}</span>
                        <span class="weekday">{{date.weekDay}}</span>
                        <div
                            class="additional"
                            v-show="date.additional"
                        >
                            <span
                                class="year"
                                v-show="date.additional.year"
                            >{{date.additional.year}}</span>
                            <span
                                class="month"
                                v-show="date.additional.month"
                            >{{date.additional.month}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="b-calendar__swipemonths">
            <div class="b-calendar__previous">
                <button
                    id="subtractMonthBtn"
                    class="btn btn-link arrow arrow-left"
                    @click="subtractMonth"
                >
                    « {{ previousMonthAsString | capitalize }}
                </button>
            </div>
            <div class="b-calendar__next">
                <button
                    id="addMonthBtn"
                    class="btn btn-link arrow arrow-right"
                    @click="addMonth"
                >
                    {{ nextMonthAsString | capitalize }} »
                </button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
// sorgente: https://codepen.io/sanya_misharin/pen/vQBeLV
// sorgente eventi: https://codepen.io/sanya_misharin/pen/bQgjog
import moment from 'moment'

export default {
    name: 'UiCalendar',
    data: function () {
        return {
            today: moment(),
            dateContext: moment(),
            selectedDate: moment(),
            days: ["L", "M", "M", "G", "V", "S", "D"],
        }
    },
    computed: {
        year: function () {
            return this.dateContext.format("Y");
        },

        month: function () {
            return this.dateContext.format("MMMM");
        },

        daysInMonth: function () {
            return this.dateContext.daysInMonth();
        },

        currentDate: function () {
            return this.dateContext.get("date");
        },

        firstDayOfMonth: function () {
            let firstDay = moment(this.dateContext).subtract(this.currentDate, "days");
            return firstDay.weekday();
        },

        previousMonth: function () {
            return moment(this.dateContext).subtract(1, "month");
        },
        previousMonthAsString: function () {
            return this.previousMonth.format("MMMM");
        },
        nextMonth: function () {
            return moment(this.dateContext).add(1, "month");
        },
        nextMonthAsString: function () {
            return this.nextMonth.format("MMMM");
        },

        daysInPreviousMonth: function () {
            return this.previousMonth.daysInMonth();
        },
        daysFromPreviousMonth: function () {
            let daysList = [];
            let count = this.daysInPreviousMonth - this.firstDayOfMonth;
            while (count < this.daysInPreviousMonth) {
                count++;
                daysList[count] = count;
            }

            return daysList.filter(function () {
                return true;
            });
        },
        dateList: function () {
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
            this.daysFromPreviousMonth.forEach(function (dayFromPreviousMonth) {
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

            return dateList.filter(function () {
                return true;
            });
        },
        initialDate: function () {
            return this.formattingDay(this.today.get("date"));
        },
        initialMonth: function () {
            return this.today.format("MMMM");
        },
        initialYear: function () {
            return this.today.format("Y");
        },
        todayInCurrentMonthAndYear: function () {
            return (
                this.month === this.initialMonth &&
                this.year === this.initialYear
            );
        },
        selectedDayAndMonth: function () {
            let dayAndMonth = this.selectedDate.format("D MMMM");
            dayAndMonth = dayAndMonth.split(" ");
            dayAndMonth = {
                day: dayAndMonth[0],
                month: dayAndMonth[1]
            };

            return dayAndMonth;
        },
        selectedWeekDay: function () {
            return this.selectedDate.format("dddd");
        },
        todayIsEqualSelectDate: function () {
            return (
                this.selectedDate.format("YYYYMMDD") ===
                this.today.format("YYYYMMDD")
            );
        }
    },
    methods: {
        addMonth: function () {
            this.dateContext = this.nextMonth;
        },
        subtractMonth: function () {
            this.dateContext = this.previousMonth;
        },
        setSelectedDate: function (moment) {
            this.selectedDate = moment;
        },
        goToday: function () {
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
        },
        dateIsEqualSelectDate: function (date) {
            return (
                this.selectedDate.format("YYYYMMDD") ===
                date.moment.format("YYYYMMDD")
            );
        },
    },
    filters: {
        capitalize: function (value) {
            if (!value) return "";
            value = value.toString();
            return value.charAt(0).toUpperCase() + value.slice(1);
        },

    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
.b-calendar {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 70rem;
    margin-bottom: $spacer * $line-height-base;

    &__header {
        width: 100%;
        border-top: 1px solid $black;

        span {
            text-transform: uppercase;
        }
    }

    &__weekdays {
        border-top: 1px solid $black;
        width: 100%;
        display: flex;
        justify-content: space-between;

        .weekday {
            width: calc(100% / 7);
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }

    &__calendar {
        border-top: 1px solid $black;
        width: 100%;
    }

    &__dates {
        display: flex;
        flex-wrap: wrap;
        position: relative;

        .date {
            width: calc(100% / 7);
            height: $spacer * 2;
            border-bottom: 1px solid $black;
            display: flex;
            justify-content: center;
            align-items: center;

            &.selected {
                position: relative;
                &::before {
                    content: '';
                    width: $spacer * 1.6;
                    height: $spacer * 1.6;
                    position: absolute;
                    @include border-radius(50%);
                    background-color: rgba($red, .8);
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    z-index: -1;
                }
            }

            .weekday {
                display: none;
            }

            &__information {
                &.today {
                    position: relative;
                    &::before {
                        content: '';
                        width: $spacer * 1.9;
                        height: $spacer * 1.9;
                        position: absolute;
                        @include border-radius(50%);
                        background-color: rgba($primary, .5);
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        z-index: -1;
                    }
                }

                &.blank {
                    opacity: 0;
                }
            }
        }
    }
    &__swipemonths {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    //
    //
    // &__previous {
    //     width: 100%;
    // }
}
</style>
