package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class DungeonSpec extends Specification implements DomainUnitTest<Dungeon> {

    def setup() {}

    def cleanup() {}

    void "enough floors"() {
        expect:
        domain.getFloors() > 2
    }
}
