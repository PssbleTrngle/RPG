package rpg

import grails.testing.gorm.DomainUnitTest
import spock.lang.Specification

class ItemTypeSpec extends Specification implements DomainUnitTest<ItemType> {

    def setup() {}

    def cleanup() {}

    void "no loops"() {
        def findLoop
        findLoop = { ItemType it ->
            if(it.getId() == this.getId())
                return true;
            for(ItemType type : it.evolvesFrom())
                if(findLoop(type)) return true
            return false
        }

        expect:
        !findLoop(domain)
    }
}
