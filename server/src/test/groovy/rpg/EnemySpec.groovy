package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class EnemySpec extends Specification implements DomainUnitTest<Enemy> {

    def setup() {}

    def cleanup() {}

    void "in battle"() {
        expect:
        domain.getBattle() != null
    }
}
