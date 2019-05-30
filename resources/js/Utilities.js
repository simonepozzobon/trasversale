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

export {
    clone,
    isEqual,
    isFile,
    SizeUtil,
    Uuid,
}
