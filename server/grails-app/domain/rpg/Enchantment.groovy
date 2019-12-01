package rpg

import grails.rest.Resource

@Resource(uri = '/enchantment', readOnly = true)
class Enchantment implements Translated {

    String id

    static constraints = {
        id bindable: true, generator: 'assigned'
    }
}
