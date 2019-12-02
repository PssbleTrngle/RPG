package rpg

import grails.rest.Resource

@Resource(uri = '/effect', readOnly = true)
class Effect implements Translated {

    String id
    int fade_min, fade_max
    boolean block = false

    static constraints = {
        id bindable: true, generator: 'assigned'
        fade_min unsigned: true
        fade_max unsigned: true
    }
}
