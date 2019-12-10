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
            .findAll({ it.getTo().getId() == this.getId() })
            .collect({ it.getFrom() });
    }
    }

    Set<Clazz> evolvesFrom() {
        Evolution
            .findAll({ it.getFrom().getId() == this.getId() })
            .collect({ it.getTo() });
    }

    int stage() {

        Set<Clazz> from = this.evolvesFrom();
        if(from.isEmpty()) 
            return 1;

        return 1 + from.first().stage();
    }

    void createEvolutions(int level, String... clazzes) {
        Evolution.withTransaction {
            clazzes.collect({ Clazz.get(it) }).filter({ it != null }).each({
                new Evolution(from: this, to: it, level: level).save();
            })
        }
    }

}
