package rpg

import grails.rest.Resource

@Resource(uri = '/effect', readOnly = true)
class Effect implements Translated {

    String id
    int fadeMin, fadeMax
    boolean block = false

    static constraints = {
        id bindable: true, generator: 'assigned'
        fadeMin unsigned: true
        fadeMax unsigned: true
    }
}
