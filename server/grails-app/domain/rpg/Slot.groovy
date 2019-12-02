package rpg

import grails.rest.Resource

@Resource(uri = '/slot', readOnly = true)
class Slot implements Translated {

    String id
    int space = 1
    boolean applyStats = false

    static constraints = {
        id bindable: true, generator: 'assigned'
    }
}
