package rpg

import grails.rest.Resource

@Resource(uri = '/rarity', readOnly = true)
class Rarity implements Translated {

    String id, color

    static constraints = {
        id bindable: true, generator: 'assigned'
        color nullable: true
    }
}
