package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class ParticipantSpec extends Specification implements DomainUnitTest<Participant> {

    def setup() {}

    def cleanup() {}

    void "correct health"() {
        expect: (domain.getHealth() > 0 || domain.getBattle() != null) && domain.getHealth() <= domain.maxHealth()
    }

    void "correct max health"() {
        expect:
        domain.maxHealth() > 0
    }

    void "on side"() {
        expect:
        domain.getSide() > 0 || domain.getBattle() == null
    }

}
