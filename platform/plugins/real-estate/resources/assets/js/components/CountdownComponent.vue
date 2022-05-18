<template>
    <div class="promotion">
        <p v-if="expired">{{ this.expiredText }}</p>
        <p v-else>
            {{ this.text }}
            <span>{{ remaining.days }} {{ __('promo_Days')}} </span>
            <span>{{ remaining.hours }} {{ __('promo_Hours')}} </span>
            <!-- <span>{{ remaining.minutes }} Minutes </span> -->
            <span>{{ remaining.seconds }} {{ __('promo_Seconds')}} </span>
        </p>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        data: function() {
            return {
                now: new Date(),
            };
        },
        props: {
            until: {
                type: String,
                default: () => null,
                required: true
            },
            text: {
                type: String,
                default: 'Promotion will end '
            },
            expiredText: {
                default: 'Promotion expired'
            }
        },
        created() {
            let interval = this.refreshEverySecond();

            this.$on('expired', () => clearInterval(interval));
        },
        computed: {
            expired () {
                return this.remaining.total <= 0;
            },

            remaining () {
                let remaining = moment.duration(Date.parse(this.until) - this.now);

                if (remaining <= 0) { 
                    this.$emit('expired');
                }

                return {
                    total: remaining,
                    days: remaining.days(),
                    hours: remaining.hours(),
                    minutes: remaining.minutes(),
                    seconds: remaining.seconds()
                };
            }
        },
        methods: {
            refreshEverySecond() {
                return setInterval(() => {
                    this.now = new Date();
                }, 1000);
            }
        }
    }
</script>
