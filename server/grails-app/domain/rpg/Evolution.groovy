package rpg

import grails.rest.Resource

@Resource(uri = '/evolution', readOnly = true)
class Evolution {

    int level

    static belongsTo = [ from: Clazz, to: Clazz ]

    static constraints = {
    }

    static void createEvolutions(String from, int level, String... to) {
        Clazz fromClazz = Clazz.get(from)
        assert fromClazz != null : "class '$from' not found"
        withTransaction {
            to.each({
                Clazz toClazz = Clazz.get(it)
                assert toClazz != null : "class '$it' not found"
                new Evolution(from: fromClazz, to: toClazz, level: level).save(failOnError: true)
            })
        }
    }
}
