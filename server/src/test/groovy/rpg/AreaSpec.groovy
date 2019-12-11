package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class AreaSpec extends Specification implements DomainUnitTest<Area> {

    def setup() {}

    def cleanup() {}

    void "no loops"() {
        def findLoop
        findLoop = { Area it ->
            if(it.getId() == domain.getId())
                return true;
            for(Area child : it.getChildren())
                if(findLoop(child)) return true
            return false
        }

        expect:
        !findLoop(domain)
    }
}
