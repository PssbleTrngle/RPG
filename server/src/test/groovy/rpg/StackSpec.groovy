package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class StackSpec extends Specification implements DomainUnitTest<Stack> {

    def setup() {}

    def cleanup() {}

    void "stackable amount"() {
        expect:
        domain.getAmount() == 1 || domain.getItem()?.getType()?.isStackable()
    }
}
