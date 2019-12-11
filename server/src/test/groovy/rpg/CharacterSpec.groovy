package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class CharacterSpec extends Specification implements DomainUnitTest<Character> {

    def setup() {}

    def cleanup() {}

    void "no future dates"() {
        expect:
        domain.getDateCreated() < new Date()
    }

    void "has class"() {
        expect:
        !domain.getClazzes().isEmpty()
    }

}
