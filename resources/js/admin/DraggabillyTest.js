/* eslint-disable brace-style */

import Vue from 'vue'
import jQuery from 'jquery'
import 'jquery-ui/ui/widgets/draggable'
import 'jquery-ui/ui/widgets/resizable'
import 'jquery-ui/themes/base/core.css'
import 'jquery-ui/themes/base/resizable.css'
import 'jquery-ui/themes/base/draggable.css'

import Draggabilly from 'draggabilly'
import {packeryEvents} from './PackeryTest'
const draggabillyPlugin = () => {}
export default draggabillyPlugin

draggabillyPlugin.install = function (Vue, options)
{
    Vue.directive('draggabilly', {
        inserted (el)
        {
            let container = el.parentNode
            let domItems = container.getElementsByClassName('packery-item')
            let items = jQuery(domItems)
            el.draggie = items.draggable().resizable({
                containment: container
            }) //new Draggabilly(el)
            packeryEvents.$emit('draggie', {draggie: el.draggie, node: el.parentNode, items: items})
        },
        unbind (el)
        {
            // el.draggie.destroy()
            el.draggie = null
        }
    })
}
