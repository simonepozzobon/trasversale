const SizeUtil = {
    get: function(el) {
        let rect = el.getBoundingClientRect()
        let computedStyle = getComputedStyle(el)
        let height = rect.height
        let width = rect.width
        let paddingY = 0
        let paddingX = 0
        let marginY = 0
        let marginX = 0
        // console.dir(rect);

        if (getComputedStyle) {
            paddingY = parseFloat(computedStyle.paddingTop) + parseFloat(computedStyle.paddingBottom)
            paddingX = parseFloat(computedStyle.paddingLeft) + parseFloat(computedStyle.paddingRight)
            marginY = parseFloat(computedStyle.marginTop) + parseFloat(computedStyle.marginBottom)
            marginX = parseFloat(computedStyle.marginLeft) + parseFloat(computedStyle.marginRight)

            height -= paddingY
            width -= paddingX
        }

        return {
            w: rect.width,
            h: rect.height,
            wClean: width,
            hClean: height,
            paddingX: paddingX,
            paddingY: paddingY,
            marginX: marginX,
            marginY: marginY,
        }
    }
}

const Uuid = {
    get: function() {
        // https://gist.github.com/6174/6062387
        return [...Array(10)].map(i=>(~~(Math.random()*36)).toString(36)).join('')
    }
}

// https://helloacm.com/how-to-clone-variables-the-clone-function-in-javascript/
const clone = function(Obj) {
    let buf;
    if (Obj instanceof Array) {
        buf = []; // create an empty array
        let i = Obj.length;
        while (i --) {
            buf[i] = clone(Obj[i]); // recursively clone the elements
        }
        return buf;
    } else if (Obj instanceof Object) {
        buf = {}; // create an empty object
        for (let k in Obj) {
            buf[k] = clone(Obj[k]); // recursively clone the value
        }
        return buf;
    } else {
        return Obj;
    }
}

const isEqual = require('lodash.isequal')

const isFile = function(obj) {
    if (obj instanceof File) {
        return true
    }
    return false
}

const orderBy = require('lodash.orderby')

const sortModules = function(modules) {
    let sorted = orderBy(modules, ['order', 'created_at'], ['asc', 'asc'])

    let formattedContents = sorted.map(module => {
        if (module.type === 'row') {

            let rowContent = module.content
            if (typeof rowContent == 'string') {
                rowContent = JSON.parse(rowContent)
            }

            let sortedColumns = orderBy(rowContent, ['order', 'created_at'], ['asc', 'asc'])
            let formattedColumns = sortedColumns.map(column => {

                    let columnContent = column.content
                    if (typeof columnContent == 'string') {
                        columnContent = JSON.parse(columnContent)
                    }
                    // console.log(columnContent);


                    let sortedModules = []
                    let modulesArr = []

                    if (columnContent.hasOwnProperty('modules')) {
                        modulesArr = Array.from(columnContent.modules)

                        sortedModules = orderBy(modulesArr, ['order', 'created_at'], ['asc', 'asc'])

                    }

                    let formattedColumn = {
                        size: columnContent.size,
                        modules: JSON.stringify(sortedModules)
                    }

                    column.content = JSON.stringify(formattedColumn)

                return column
            })

            module.content = JSON.stringify(rowContent)
        }

        return module
    })

    return formattedContents
}

const checkOverflow = function (el) {
    // Determines if the passed element is overflowing its bounds,
    // either vertically or horizontally.
    // Will temporarily modify the "overflow" style to detect this
    // if necessary.
    let curOverflow = el.style.overflow

    if ( !curOverflow || curOverflow === "visible" ) {
        el.style.overflow = "hidden"
    }

    let isOverflowing = el.clientWidth < el.scrollWidth || el.clientHeight < el.scrollHeight
    // console.log(el, el.clientWidth, el.scrollWidth,el.clientHeight, el.scrollHeight);

    el.style.overflow = curOverflow

    return isOverflowing
}

const checkDuplicateInObject = function (propertyName, inputArray) {
    let seenDuplicate = false,
    itemProp = null,
    testObject = {}

    inputArray.map(function(item) {
        let itemPropertyName = item[propertyName]

        if (itemPropertyName in testObject) {
            testObject[itemPropertyName].duplicate = true
            item.duplicate = true
            seenDuplicate = true
            itemProp = item[propertyName]
        }
        else {
            testObject[itemPropertyName] = item
            delete item.duplicate
        }
    })

    return {
        check: seenDuplicate,
        prop: itemProp
    }
}


export {
    checkDuplicateInObject,
    checkOverflow,
    clone,
    isEqual,
    isFile,
    SizeUtil,
    sortModules,
    Uuid,
}
