package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class AccountSpec extends Specification implements DomainUnitTest<Account> {

    def setup() {}

    def cleanup() {}

    void "has selected"() {
        expect:
        domain.getSelected() != null || domain.getCharacters().isEmpty()
    }

    void "valid selected"() {
        expect:
        domain.getSelected() == null || domain.getCharacters().find({ it.getId() == domain.getSelected().getId() })
    }

    void "no future dates"() {
        expect:
        domain.getDateCreated() < new Date()
    }
}
