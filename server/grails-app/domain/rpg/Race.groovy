package rpg

import grails.rest.Resource

@Resource(uri = '/race', readOnly = true)
class Race implements Translated {

    String id

    static belongsTo = [ stats: Stats ]

    static constraints = {
        id bindable: true, generator: 'assigned'
        stats nullable: false
    }
}
