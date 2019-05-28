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

export {
    SizeUtil,
    Uuid,
}
