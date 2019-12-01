package rpg

import grails.rest.Resource

@Resource(uri = '/item', readOnly = true)
class Item implements Translated {

    String id

    static belongsTo = [type: ItemType, rarity: Rarity, stats: Stats ]

    static constraints = {
        id bindable: true, generator: 'assigned'
        stats nullable: true
        rarity nullable: true
    }
}
