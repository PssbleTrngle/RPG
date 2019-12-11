package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class NpcSpec extends Specification implements DomainUnitTest<Npc> {

    def setup() {}

    def cleanup() {}

    void "max health"() {
        expect:
        domain.getMaxHealth() > 0
    }
}
