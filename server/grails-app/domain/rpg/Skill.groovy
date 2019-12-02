package rpg

import grails.rest.Resource

@Resource(uri = '/skill', readOnly = true)
class Skill implements Translated {

    String id
    int timeout, charge = 0, cost, range
    boolean affectDead = false

    static constraints = {
        id bindable: true, generator: 'assigned'
    }
}
