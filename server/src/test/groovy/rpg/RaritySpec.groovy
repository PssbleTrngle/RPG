package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class RaritySpec extends Specification implements DomainUnitTest<Rarity> {

    def setup() {}

    def cleanup() {}

    void "correct color"() {
        expect:
        domain.getColor() == null || domain.getColor().matches(/#[0-9A-F]{6}/)
    }
}
