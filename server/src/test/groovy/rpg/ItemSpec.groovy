package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class ItemSpec extends Specification implements DomainUnitTest<Item> {

    def setup() {}

    def cleanup() {}

    void "useful stats"() {
        Stats stats = domain.getStats()
        expect:
        stats == null || stats.total() > 0
    }
}
