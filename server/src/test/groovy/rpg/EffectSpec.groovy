package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class EffectSpec extends Specification implements DomainUnitTest<Effect> {

    def setup() {}

    def cleanup() {}

    void "correct fade span"() {
        expect:
        domain.getFadeMin() <= domain.getFadeMax()
    }
}
