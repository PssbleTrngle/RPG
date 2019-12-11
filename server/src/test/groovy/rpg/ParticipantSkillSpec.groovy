package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class ParticipantSkillSpec extends Specification implements DomainUnitTest<ParticipantSkill> {

    def setup() {}

    def cleanup() {}

    void "in battle or ready"() {
        expect:
        domain.getNextUse() == 0 || domain.getParticipant().getBattle() != null
    }

    void "correct next use"() {
        expect:
        domain.getNextUse() <= domain.getSkill().getTimeout()
    }

}
