"use strict";

(function (window, $) {

    window.RepLogApp = function ($wrapper) {
        this.$wrapper = $wrapper
        this.helper = new Helper($wrapper)

        $wrapper.on(
            "click",
            ".js-delete-rep-log",
            this.handleDeleteRepLog.bind(this)
        )


        $wrapper.on(
            "submit",
            this._selectForm._selector,
            this.handleFormRepLog.bind(this)
        )

    }

    $.extend(window.RepLogApp.prototype, {

        _selectForm: {
            _selector: '.js-form-submit'
        },

        handleDeleteRepLog: function (e) {
            e.preventDefault()

            let $element = $(e.currentTarget)
            let $url = $element.data("url")
            let $row = $element.closest("tbody tr")
            let self = this

            this.helper.changeTrashElementToSnipper($element)

            $.ajax({
                url: $url,
                method: "DELETE"
            }).then(function () {
                $row.fadeOut("normal", function () {
                    $row.remove()
                    self.$wrapper
                        .find(".js-sumLogs")
                        .html(self.helper.calculateTotalWeightLifted())
                })
            }).catch(function () {

            })
        },

        handleFormRepLog: function (e) {
            e.preventDefault()

            let $element = $(e.currentTarget)
            let $url = $element.data("url")
            let $arrayElement = {}
            let self = this

            $.each($element.serializeArray(), function (key, fieldName) {
                $arrayElement[fieldName.name] = fieldName.value
            })

            $.ajax({
                url: $url,
                data: JSON.stringify($arrayElement),
                method: "POST"
            }).then(function (data) {
                self._cleanForm()
                self._addRow(data)
            }).catch(function (jqXHR) {
                let $errors = JSON.parse(jqXHR.responseText)
                self._showErrorsInForm($errors.errors)
            })
        },

        _showErrorsInForm: function ($errors) {
            let $form = $(this.$wrapper).find(this._selectForm._selector)
            this._cleanForm()

            $form.find(":input").each(function () {
                let $elementName = $(this).attr("name")
                let $parent = $(this).closest(".form-group")

                if (!$errors[$elementName]) {
                    return
                }
                let $span = $('<span class="text-danger js-delete-span"></span>')
                $span.html($errors[$elementName])
                $parent.append($span)
                $parent.addClass("js-delete-group-form")
            })
        },

        _cleanForm: function (){
            console.log(this)
            let $form = $(this.$wrapper).find(this._selectForm._selector)
            $form.find(".js-delete-group-form").removeClass("js-delete-group-form")
            $form.find(".js-delete-span").remove()
        },

        _addRow: function ($repLog){
            // this.$wrapper.find("tbody tr")
        }
    })


    let Helper = function ($wrapper) {
        this.$wrapper = $wrapper
    }

    $.extend(Helper.prototype, {

        calculateTotalWeightLifted: function () {
            let $totalWeight = 0
            $(this.$wrapper.find("tbody tr")).each(function () {
                $totalWeight += $(this).data("weight")
            })
            return $totalWeight
        },

        changeTrashElementToSnipper: function ($element) {
            $element
                .find(".fa")
                .removeClass("fa-trash")
                .addClass("fa-spinner")
                .addClass("fa-spin")
        },
    })

})(window, jQuery)