import {
    Uuid
}
from '../../../Utilities'

const formatEl = function (post, i = 0, elements = []) {
    let colN = 12
    // console.log(post.width);
    let w = post.hasOwnProperty('width') ? post.width : 2
    let h = post.hasOwnProperty('height') ? post.height : 2

    let x = post.hasOwnProperty('x') ? post.x : (i * w) % colN
    let y = post.hasOwnProperty('y') ? post.y : Math.floor((i * w) / colN)
    let uuid = post.hasOwnProperty('uuid') ? post.uuid : Uuid.get()
    // console.log(w, h);

    const cache = Object.assign({}, post)
    delete cache.id

    const newEl = {
        i: i,
        x: Number(x),
        y: Number(y),
        w: Number(w),
        h: Number(h),
        idx: elements.length,
        uuid: uuid,
        order: i,
        type_id: post.id,
        ...cache,
    }
    return newEl
}


export {
    formatEl,
}
