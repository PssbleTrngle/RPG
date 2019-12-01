package rpg

import grails.rest.Resource

@Resource(uri = '/rank', readOnly = true)
class Rank implements Translated {

    String id

    static constraints = {
        id bindable: true, generator: 'assigned'
    }
}
