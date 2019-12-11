package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class BattleSpec extends Specification implements DomainUnitTest<Battle> {

    def setup() {}

    def cleanup() {}

    void "has fields"() {
        expect:
        !domain.getFields().isEmpty()
    }

    void "has participants"() {
        expect:
        !domain.getParticipants().isEmpty()
    }
}
