package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class ClazzSpec extends Specification implements DomainUnitTest<Clazz> {

    def setup() {
    }

    def cleanup() {
    }

    void "stage"() {

        int stage = domain.stage()
        expect: stage >= 1 && stage <= 4;

    }

    void "no loops"() {

        def find = { Clazz clazz ->
            if(clazz.getId() == this.getId())
                return true;
            for(Clazz from : clazz.evolvesFrom())
                if(find(from)) return true;
            return false;
        }

        expect: find(domain) == false

    }

    void "evolutions"() {

        def fromStages = domain
                            .evolvesFrom()
                            .collect({ it.stage() })
                            .unique()

        /** If there are precessor classes, all should have he same stage */
        expect: fromStages.size() <= 1;
            
    }

    void "stats"() {

        int total = domain.getStats().total();
        int stage = domain.getStage();

        expect: total == (90 + stage * 10)

    }

}
