package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class ParticipantEffectSpec extends Specification implements DomainUnitTest<ParticipantEffect> {

    def setup() {}

    def cleanup() {}

    void "in battle"() {
        expect:
        domain.getParticipant().getBattle() != null
    }
}
