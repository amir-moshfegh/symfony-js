'use strict';

(function (window, $) {

    window.RepLogApp = function ($wrapper) {
        this.$wrapper = $wrapper
        this.helper = new Helper($wrapper)

        $wrapper.find('tbody tr').on(
            'click',
            this.handelRowClick.bind(this)
        )
        $wrapper.find('.js-delete-rep-log').on(
            'click',
            this.handelRepLogDelete.bind(this)
        )
        $wrapper.find('.js-delete-rep-log-form').on(
            'submit',
            this.handelRepLogForm.bind(this)
        )
    }

    $.extend(window.RepLogApp.prototype, {
        handelRowClick: function () {
            console.log('row')
        },

        handelRepLogDelete: function (e) {
            e.preventDefault()
            const $link = $(e.currentTarget);
            const $url = $link.data('url');
            const $row = $link.closest('tr');

            $link
                .find('.fa')
                .removeClass('fa-trash')
                .addClass('fa-spinner')
                .addClass('fa-spin')

            let self = this

            $.ajax({
                method: 'DELETE',
                url: $url,
                success: function () {
                    $row.fadeOut('normal', function () {
                        $row.remove()
                        self.$wrapper.find('.js-sumLogs').html(self.helper.calculateTotalWeight())
                    })
                }
            })
        },

        handelRepLogForm: function (e) {
            e.preventDefault()
            let $form = $(e.currentTarget);

            $.ajax({
                method: 'POST',
                url: $form.attr('action'),
                data: $form.serialize()
            })
        }
    })


    function Helper($wrapper) {
        this.$wrapper = $wrapper
    }

    $.extend(Helper.prototype, {

        calculateTotalWeight: function () {
            let totalWeight = 0
            this.$wrapper.find('tbody tr').each(function () {
                totalWeight += $(this).data('weight') || 0
            })
            return totalWeight
        }

    })


})(window, jQuery)