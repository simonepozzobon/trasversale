<template>
    <div class="search-input form-inline my-2 my-lg-0">
        <input
            class="search-input__input form-control mr-sm-2"
            type="search"
            placeholder="Search"
            aria-label="Search"
            v-model="search"
            @keyup.enter="checkSearch"
        />
    </div>
</template>

<script>
import { Uuid } from "../Utilities";
export default {
    name: "UiSimpleSearch",
    data: function() {
        return {
            search: null,
            master: null
        };
    },
    // watch: {
    //     search: function (str) {
    //         if (str != null) {
    //             if (str.length > 1) {
    //                 this.startSearch()
    //             }
    //         }
    //     },
    // },
    methods: {
        debug: function() {
            this.search = "storie";
        },
        checkSearch: function() {
            if (this.search != null && this.search.length > 1) {
                this.startSearch();
            }
        },
        startSearch: function() {
            // console.log('start', this.search);
            if (this.search && this.search.length > 1) {
                let data = {
                    string: this.search
                };
                this.$http.post("/api/search", data).then(response => {
                    // console.log(response.data);
                    this.formatResults(response.data);
                });
            }
        },
        formatResults: function(results) {
            this.$root.search = this.search;
            // this.search = null
            let formatted = [];
            for (let i = 0; i < results.length; i++) {
                let result = results[i];
                result.uuid = Uuid.get();
                formatted.push(result);
            }

            this.$root.searchResults = formatted;
            this.$nextTick(() => {
                this.$root.goTo("search");
            });
        },
        resetSearch: function() {
            this.search = "";
        }
    },
    mounted: function() {
        // this.$nextTick(() => this.debug())
    }
};
</script>

<style lang="scss" scoped>
@import "~styles/shared";

.search-input {
    justify-content: flex-end;
    width: 140px;

    @include media-breakpoint-up("lg") {
        width: auto;
    }
    // &__input {
    // width: 80%;
    // }
}
// .ui-simple-search {
//     max-width: 100%;
//     margin-bottom: $spacer;
//     display: flex;
//     align-items: center;
//     position: relative;
//     $self: &;
//
//     &__input {
//         @include border-radius($border-radius);
//         // background-color: $faded;
//         width: 100%;
//         padding: $input-padding-y $spacer * 2 $input-padding-y $input-padding-x;
//         box-shadow: $input-box-shadow;
//         line-height: $input-line-height;
//         background-image: none;
//         background-clip: padding-box;
//         border: $input-btn-border-width solid $input-border-color;
//         transition: $transition-base;
//
//         &::placeholder {
//             color: $input-color-placeholder;
//             // Override Firefox's unusual default opacity; see https://github.com/twbs/bootstrap/pull/11526.
//             opacity: 0.4;
//             transition: $transition-base;
//         }
//
//         &:focus {
//             color: $input-color-focus;
//             background-color: $input-bg-focus;
//             border-color: $input-border-focus;
//             outline: none;
//             transition: $transition-base;
//         }
//
//         &:focus + #{$self}__icon {
//             color: $primary !important;
//             opacity: 1 !important;
//             transition: $transition-base;
//         }
//
//     }
//
//     &__icon {
//         position: absolute;
//         right: 0;
//         padding: $input-padding-y $input-padding-x;
//         opacity: 0.4;
//         transition: $transition-base;
//     }
//
//     &__times {
//         position: absolute;
//         right: 0;
//         padding: $input-padding-y $input-padding-x;
//         opacity: 0.4;
//         cursor: pointer;
//         transition: $transition-base;
//
//         display: none;
//     }
//
// }
</style>
