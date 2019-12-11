package rpg

import grails.rest.Resource

@Resource(uri = '/class', readOnly = true)
class Clazz implements Translated {

    String id

    static belongsTo = [ stats: Stats, starter_weapon: ItemType ]

    static constraints = {
        id bindable: true, generator: 'assigned'
        starter_weapon nullable: true
    }

    Set<Clazz> evolvesFrom() {
        Evolution
            .findAllByTo(this)
            .collect({ it.getFrom() })
    }

    Set<Clazz> evolvesTo() {
        Evolution
            .findAllByFrom(this)
            .collect({ it.getTo() })
    }

    int stage() {

        Set<Clazz> from = this.evolvesFrom();
        if(from.isEmpty()) 
            return 1;

        return 1 + from.first().stage();
    }

}
